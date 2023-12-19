<template>
<tr>
    <td colspan="2">
        <input type="text" class="form-control" v-model="document" />
    </td>
    <td>&nbsp;</td>
    <td><button class="btn btn-sm btn-secondary" @click="clearFilter()">Clear</button></td>
</tr>
</template>

<script>
export default {
    name: "DocumentsListFilter",
    // store,
    data() {
        return {
            document: "",
        }
    },

    mounted() {
    },

    methods: {
        clearFilter() {
            this.document = "";
        }
    },

    watch: {
        document(value) {
            this.$emit("filter:document", value);
        },
    }
}
</script>

<style scoped>

</style>
