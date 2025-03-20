<template>
    <div>
        {{ $store.state.teste }}
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" v-for="(t, key) in titulos" :key="key">
                        {{ t.titulo }}
                    </th>
                    <th
                        v-if="
                            visualizar.visivel ||
                            atualizar.visivel ||
                            remover.visivel
                        "
                    ></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(obj, chave) in dadosFiltrados" :key="chave">
                    <td v-for="(valor, chaveValor) in obj" :key="chaveValor">
                        <span v-if="titulos[chaveValor].tipo == 'text'">{{
                            valor
                        }}</span>
                        <span v-if="titulos[chaveValor].tipo == 'data'">
                            {{ valor | formataDataTempoGlobal }}
                        </span>
                        <span v-if="titulos[chaveValor].tipo == 'imagem'">
                            <img
                                :src="'/storage/' + valor"
                                width="50"
                                height="50'"
                            />
                        </span>
                    </td>
                    <td v-if="visualizar || atualizar.visivel || remover">
                        <button
                            v-if="visualizar.visivel"
                            class="btn btn-outline-primary btn-sm"
                            :data-toggle="visualizar.dataToggle"
                            :data-target="visualizar.dataTarget"
                            @click="setStore(obj)"
                        >
                            Visualizar
                        </button>
                        <button
                            v-if="atualizar.visivel"
                            :data-toggle="atualizar.dataToggle"
                            :data-target="atualizar.dataTarget"
                            class="btn btn-outline-primary btn-sm"
                            @click="setStore(obj)"
                        >
                            Atualizar
                        </button>
                        <button
                            v-if="remover.visivel"
                            class="btn btn-outline-danger btn-sm"
                            :data-toggle="remover.dataToggle"
                            :data-target="remover.dataTarget"
                            @click="setStore(obj)"
                        >
                            Remover
                        </button>
                    </td>
                    <!----
                    <th scope="row">{{ m.id }}</th>
                    <td>{{ m.nome }}</td>
                    
                    <td>
                        <img
                            :src="'/storage/' + m.imagem"
                            width="50"
                            height="50"
                        />
                    </td>
                    ---->
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import { data } from "jquery";

export default {
    props: ["dados", "titulos", "visualizar", "atualizar", "remover"],
    methods: {
        setStore(obj) {
            this.$store.state.transacao.status = "";
            this.$store.state.transacao.mensagem = "";
            this.$store.state.transacao.dados = "";
            this.$store.state.item = obj;
        },
    },
    computed: {
        dadosFiltrados() {
            console.log(this.dados);
            let campos = Object.keys(this.titulos);
            let dadosFiltrados = [];

            this.dados.map((item, chave) => {
                let itemFiltrado = {};
                campos.forEach((campo) => {
                    itemFiltrado[campo] = item[campo];
                });
                dadosFiltrados.push(itemFiltrado);
            });
            return dadosFiltrados;
        },
    },
};
</script>
