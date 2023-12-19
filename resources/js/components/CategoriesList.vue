<template>
<div class="simple-list">
    <categories-list-table
        v-if="this.list !== null"

        ref="root_table"
        :fields="fields"
        :list-code="listCode"
        :full-list="list"
        :level-list="rootList"
        :current-level="1"
        :parent-id="null"
        :levels="levels"
        :the-root="this"
    ></categories-list-table>

    <h1 class="loading" v-if="0 === loadedStatus"><img src="/img/loading.gif" /></h1>

</div>
</template>

<script>
import CategoriesListTable from "./CategoriesListTable";
export default {
    name: "CategoriesList",
    components: {CategoriesListTable},
    props: ["fields", "listCode", "levels"],
    data() {
        return {
            list: null,
            editingArea: null,
            loadedStatus: 0,

        }
    },

    async mounted() {
        this.list = (await axios.get("/admin/list/"+this.listCode+"/all")).data;
        this.loadedStatus = 1;
    },

    computed: {
        rootList() {
            return this.list.filter(x => !x.parent_id);
        }
    },

}
</script>

<style scoped>
h1.loading {
    text-align: center;
}
</style>
