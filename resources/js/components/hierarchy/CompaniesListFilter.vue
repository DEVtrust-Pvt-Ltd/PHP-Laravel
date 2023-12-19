<template>
<tr>
    <td>
        <select-field id="filter_select" v-model="enterprise_id" :params="enterprises"></select-field>
    </td>
    <td colspan="2">
        <input type="text" class="form-control" v-model="company" />
    </td>
    <td>&nbsp;</td>
    <td><button class="btn btn-sm btn-secondary" @click="clearFilter()">Clear</button></td>
</tr>
</template>

<script>
import SelectField from "../form-fields/SelectField";
// import store from "../tools/store";
export default {
    name: "CompaniesListFilter",
    components: {SelectField},
    // store,
    data() {
        return {
            enterprise_id: "",
            company: "",

            enterprises: {
                url: "/admin/hierarchy/api/list/enterprises?format=code&order=code",
                empty: "Filter...",
            }
        }
    },

    mounted() {
        console.log(this.$store);
    },

    methods: {
        clearFilter() {
            this.enterprise_id = "";
            this.company = "";
        }
    },

    watch: {
        enterprise_id(value) {
            // this.$store.commit('companiesFilter', {field: "enterprise_id", value: value});
            this.$emit("filter:enterprise", value);
        },

        company(value) {
            this.$emit("filter:company", value);
        },

    }
}
</script>

<style scoped>

</style>
