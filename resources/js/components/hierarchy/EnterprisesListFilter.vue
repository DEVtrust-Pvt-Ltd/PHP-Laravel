<template>
<tr>
    <td colspan="2">
        <input type="text" class="form-control" v-model="enterprise" />
    </td>
    <td>&nbsp;</td>
    <td><button class="btn btn-sm btn-secondary" @click="clearFilter()">Clear</button></td>
</tr>
</template>

<script>
export default {
    name: "EnterprisesListFilter",
    // store,
    data() {
        return {
            enterprise: "",
        }
    },

    mounted() {
    },

    methods: {
        clearFilter() {
            this.enterprise = "";
        }
    },

    watch: {
        enterprise(value) {
            this.$emit("filter:enterprise", value);
        },

    }
}
</script>

<style scoped>

</style>
