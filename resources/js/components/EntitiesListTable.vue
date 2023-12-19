<template>

<table class="main-list-table table table-hover" :class="extraClass">
    <thead class="thead-light">
    <tr>
        <th v-for="h in header" :class="h.class">{{ h.title }}</th>
        <th>&nbsp;</th>
    </tr>
    <slot name="extra-header"></slot>
    </thead>
    <tfoot></tfoot>
    <tbody>
    <tr v-for="(item, idx) in list" @click="rowClicked(item.item, idx, $event)" class="clickable">
        <td v-for="cell in item.row" :class="cell.class">{{ cell.value }}</td>
        <td class="controls-td">
            <button class="btn btn-primary btn-sm" @click.stop="editItem(item.item, idx, $event)" v-if="!item.noEdit">Edit</button>
            <button class="btn btn-danger btn-sm" @click.stop="deleteItem(item.item, idx, $event)" v-if="!item.noDelete">Delete</button>
        </td>
    </tr>
    </tbody>
</table>
</template>

<script>
export default {
    name: "EntitiesListTable",
    props: {
        extraClass: {
            type: String,
            default: "",
        },
        header: Array,
        list: Array,
    },

    methods: {
        rowClicked(item, idx, event) {
            this.$emit("click:row", item, idx, event);
        },

        editItem(item, idx, event) {
            this.$emit("click:edit", item, idx, event);
        },

        deleteItem(item, idx, event) {
            this.$emit("click:delete", item, idx, event);
        },
    }
}
</script>

<style scoped lang="scss">
table.main-list-table {
    width: 100%;

    thead th {
        font-weight: bold;
    }

    td.controls-td {
        width: 5%;
        white-space: nowrap;
    }

    td.boolean {
        /*text-align: center;*/
    }

    tbody tr.clickable {
        cursor: pointer;
    }
}

</style>
