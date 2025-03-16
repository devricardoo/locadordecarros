<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Buscas do card de busca -->
                <card-component titulo="Buscas de marcas">
                    <template v-slot:conteudo>
                        <div class="form-row">
                            <div class="col mb-3">
                                <input-container-component
                                    titulo="ID"
                                    id="inputId"
                                    id-help="idHelp"
                                    texto-help="Opcional. Informe o ID da marca"
                                >
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="inputId"
                                        aria-describedby="idHelp"
                                        placeholder="ID"
                                        v-model="busca.id"
                                    />
                                </input-container-component>
                            </div>

                            <div class="col mb-3">
                                <input-container-component
                                    titulo="Nome da marca"
                                    id="inputNome"
                                    id-help="nomeHelp"
                                    texto-help="Opcional. Informe o nome da marca"
                                >
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="inputNome"
                                        aria-describedby="nomeHelp"
                                        placeholder="Nome da marca"
                                        v-model="busca.nome"
                                    />
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button
                            type="button"
                            class="btn btn-primary btn-sm float-right"
                            @click="pesquisar()"
                        >
                            Pesquisar
                        </button>
                    </template>
                </card-component>
                <!-- Fim Buscas do card de busca -->

                <!-- início do card de listagem de marcas -->
                <card-component titulo="Relação de marcas">
                    <template v-slot:conteudo>
                        <table-component
                            :dados="marcas.data"
                            :visualizar="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalMarcaVisualizar',
                            }"
                            :atualizar="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalMarcaAtualizar',
                            }"
                            :remover="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalMarcaRemover',
                            }"
                            :titulos="{
                                id: { titulo: 'ID', tipo: 'text' },
                                nome: { titulo: 'Nome', tipo: 'text' },
                                imagem: { titulo: 'Imagem', tipo: 'imagem' },
                                created_at: {
                                    titulo: 'Data criação',
                                    tipo: 'data',
                                },
                            }"
                        ></table-component>
                    </template>

                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li
                                        v-for="(l, key) in marcas.links"
                                        :key="key"
                                        :class="
                                            l.active
                                                ? 'page-item active'
                                                : 'page-item'
                                        "
                                        @click="paginacao(l)"
                                    >
                                        <a
                                            class="page-link"
                                            v-html="l.label"
                                        ></a>
                                    </li>
                                </paginate-component>
                            </div>

                            <div class="col">
                                <button
                                    type="button"
                                    class="btn btn-primary btn-sm float-right"
                                    data-toggle="modal"
                                    data-target="#modalMarca"
                                >
                                    Adicionar
                                </button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- fim do card de listagem de marcas -->
            </div>
        </div>

        <!-- inicio do modal de inclusao da marca -->
        <modal-component id="modalMarca" titulo="Adicionar marca">
            <template v-slot:alertas>
                <alert-component
                    tipo="success"
                    :detalhes="transacaoDetalhes"
                    titulo="Cadastro realizado com sucesso"
                    v-if="transacaoStatus == 'adicionado'"
                ></alert-component>
                <alert-component
                    tipo="danger"
                    :detalhes="transacaoDetalhes"
                    titulo="Erro ao cadastrar a marca"
                    v-if="transacaoStatus == 'erro'"
                ></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component
                        titulo="Nome da marca"
                        id="novoNome"
                        id-help="novoNomeHelp"
                        texto-help="Informe o nome da marca"
                    >
                        <input
                            type="text"
                            class="form-control"
                            id="inputId"
                            aria-describedby="novoNomeHelp"
                            placeholder="Nome da marca"
                            v-model="nomeMarca"
                        />
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component
                        titulo="Imagem"
                        id="novoImagem"
                        id-help="novoImagemHelp"
                        texto-help="Selecione uma imagem no formato PNG"
                    >
                        <input
                            type="file"
                            class="form-control-file"
                            id="inputId"
                            aria-describedby="novoImagemHelp"
                            placeholder="Selecione uma imagem"
                            @change="carregarImagem($event)"
                        />
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal"
                >
                    Fechar
                </button>
                <button type="button" class="btn btn-primary" @click="salvar()">
                    Salvar mudanças
                </button>
            </template>
        </modal-component>
        <!-- final do modal de inclusao da marca -->

        <!-- inicio do modal de visualizaçao daemarca -->
        <modal-component id="modalMarcaVisualizar" titulo="Visualizar marca">
            <template v-slot:alertas></template>
            <template v-slot:conteudo>
                <input-container-component titulo="ID">
                    <input
                        type="text"
                        class="form-control mb-3"
                        :value="$store.state.item.id"
                        disabled
                    />
                </input-container-component>

                <input-container-component titulo="Nome da marca">
                    <input
                        type="text"
                        class="form-control mb-3"
                        :value="$store.state.item.nome"
                        disabled
                    />
                </input-container-component>

                <input-container-component titulo="Imagem">
                    <img
                        :src="'storage/' + $store.state.item.imagem"
                        v-if="$store.state.item.imagem"
                    />
                </input-container-component>

                <input-container-component titulo="Data de criação">
                    <input
                        type="text"
                        class="form-control mb-3"
                        :value="
                            $store.state.item.created_at
                                ? new Date(
                                      $store.state.item.created_at
                                  ).toLocaleDateString()
                                : $store.state.item.created_at
                        "
                        disabled
                    />
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal"
                >
                    Fechar
                </button>
            </template>
        </modal-component>
        <!-- final do modal de visualizaçao da marca -->

        <!-- inicio do modal de remoçao da marca -->
        <modal-component id="modalMarcaRemover" titulo="Remover marca">
            <template v-slot:alertas>
                <alert-component
                    tipo="success"
                    titulo="Transação realizada com sucesso"
                    :detalhes="$store.state.transacao"
                    v-if="$store.state.transacao.status == 'sucesso'"
                ></alert-component>
                <alert-component
                    tipo="danger"
                    titulo="Erro na transação"
                    :detalhes="$store.state.transacao"
                    v-if="$store.state.transacao.status == 'erro'"
                ></alert-component>
            </template>
            <template
                v-slot:conteudo
                v-if="$store.state.transacao.status != 'sucesso'"
            >
                <input-container-component titulo="ID">
                    <input
                        type="text"
                        class="form-control mb-3"
                        :value="$store.state.item.id"
                        disabled
                    />
                </input-container-component>

                <input-container-component titulo="Nome da marca">
                    <input
                        type="text"
                        class="form-control mb-3"
                        :value="$store.state.item.nome"
                        disabled
                    />
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal"
                >
                    Fechar
                </button>
                <button
                    type="button"
                    class="btn btn-danger"
                    @click="remover()"
                    v-if="$store.state.transacao.status != 'sucesso'"
                >
                    Remover
                </button>
            </template>
        </modal-component>
        <!-- final do modal de remoçao da marca -->

        <!-- inicio do modal de atualização da marca -->
        <modal-component id="modalMarcaAtualizar" titulo="Atualizar marca">
            <template v-slot:alertas>
                <alert-component
                    tipo="success"
                    titulo="Transação realizada com sucesso"
                    :detalhes="$store.state.transacao"
                    v-if="$store.state.transacao.status == 'sucesso'"
                ></alert-component>
                <alert-component
                    tipo="danger"
                    titulo="Erro na transação"
                    :detalhes="$store.state.transacao"
                    v-if="$store.state.transacao.status == 'erro'"
                ></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component
                        titulo="Nome da marca"
                        id="atualizarNome"
                        id-help="atualizarNomeHelp"
                        texto-help="Informe o nome da marca"
                    >
                        <input
                            type="text"
                            class="form-control"
                            id="atualizarNome"
                            aria-describedby="atualizarNomeHelp"
                            placeholder="Nome da marca"
                            v-model="$store.state.item.nome"
                        />
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component
                        titulo="Imagem"
                        id="atualizarImagem"
                        id-help="atualizarImagemHelp"
                        texto-help="Selecione uma imagem no formato PNG"
                    >
                        <input
                            type="file"
                            class="form-control-file"
                            id="atualizarImagem"
                            aria-describedby="atualizarImagemHelp"
                            placeholder="Selecione uma imagem"
                            @change="carregarImagem($event)"
                        />
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal"
                >
                    Fechar
                </button>
                <button
                    type="button"
                    class="btn btn-primary"
                    @click="atualizar()"
                >
                    Atualizar
                </button>
            </template>
        </modal-component>
        <!-- final do modal de atualização da marca -->
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            urlBase: "http://localhost:8000/api/v1/marca",
            urlPaginacao: "",
            urlFiltro: "",
            nomeMarca: "",
            imagemMarca: [],
            transacaoStatus: "",
            transacaoDetalhes: {},
            marcas: { data: [] },
            busca: { id: "", nome: "" },
        };
    },
    methods: {
        atualizar() {
            let formData = new FormData();
            formData.append("_method", "patch");
            formData.append("nome", this.$store.state.item.nome);

            if (this.imagemMarca[0]) {
                formData.append("imagem", this.imagemMarca[0]);
            }

            let url = this.urlBase + "/" + this.$store.state.item.id;

            let config = {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            };

            axios
                .post(url, formData, config)
                .then((response) => {
                    this.$store.state.transacao.status = "sucesso";
                    this.$store.state.transacao.mensagem =
                        "Registro de marca atualizado com sucesso!";

                    atualizarImagem.value = "";
                    this.carregarLista();
                })
                .catch((errors) => {
                    this.$store.state.transacao.status = "erro";
                    this.$store.state.transacao.mensagem =
                        errors.response.data.message;
                    this.$store.state.transacao.dados =
                        errors.response.data.errors;
                });
        },
        remover() {
            let confirmacao = confirm(
                "Tem certeza que deseja remover esse registro?"
            );

            if (!confirmacao) {
                return false;
            }

            let url = this.urlBase + "/" + this.$store.state.item.id;
            // para teste - let url = this.urlBase + "/1300";

            let formData = new FormData();
            formData.append("_method", "delete");

            axios
                .post(url, formData)
                .then((response) => {
                    console.log("Registro removido com sucesso!", response);
                    this.$store.state.transacao.status = "sucesso";
                    this.$store.state.transacao.mensagem = response.data.msg;
                    this.carregarLista();
                })
                .catch((errors) => {
                    console.log(
                        "Houve um erro na tentativa de remoção o registro",
                        errors.response
                    );
                    this.$store.state.transacao.status = "erro";
                    this.$store.state.transacao.mensagem =
                        errors.response.data.msg;
                });
        },
        pesquisar() {
            //console.log(this.busca);

            let filtro = "";

            for (let chave in this.busca) {
                console.log(chave, this.busca[chave]);

                if (this.busca[chave]) {
                    if (filtro != "") {
                        filtro += ";";
                    }

                    filtro += chave + ":like:" + this.busca[chave];
                }
            }

            if (filtro != "") {
                this.urlPaginacao = "page=1";
                this.urlFiltro = "&filtro=" + filtro;
            } else {
                this.urlFiltro = "";
            }
            this.carregarLista();
        },
        paginacao(l) {
            if (l.url) {
                //this.urlBase = l.url; // ajustando a url de consulta com o parametro de pagina
                this.urlPaginacao = l.url.split("?")[1];
                this.carregarLista(); // requisitanto novamente os dados para nossa API
            }
        },
        carregarLista() {
            let url = this.urlBase + "?" + this.urlPaginacao + this.urlFiltro;

            axios
                .get(url)
                .then((response) => {
                    this.marcas = response.data;
                })

                .catch((errors) => {
                    console.log(errors);
                });
        },
        carregarImagem(e) {
            this.imagemMarca = e.target.files;
        },
        salvar() {
            console.log(this.nomeMarca, this.imagemMarca);

            if (!this.imagemMarca) {
                console.log("Nenhuma imagem foi selecionada!");
                return;
            }

            let formData = new FormData();
            formData.append("nome", this.nomeMarca);
            formData.append("imagem", this.imagemMarca[0]);

            let config = {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            };

            axios
                .post(this.urlBase, formData, config)
                .then((response) => {
                    this.transacaoStatus = "adicionado";
                    this.transacaoDetalhes = {
                        mensagem: "ID do registro: " + response.data.id,
                    };
                    console.log(response);
                })
                .catch((errors) => {
                    this.transacaoStatus = "erro";
                    this.transacaoDetalhes = {
                        dados: errors.response.data.errors,
                        mensagem: errors.response.data.message,
                    };
                    //console.log(errors.response);
                });
        },
    },
    mounted() {
        this.carregarLista();
    },
};
</script>
