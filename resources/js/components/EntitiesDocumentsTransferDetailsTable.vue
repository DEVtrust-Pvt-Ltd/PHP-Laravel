<template>
    <table class="main-list-table table table-hover" :class="extraClass">
        <thead class="thead-light">
        <tr>
            <th>E Id </th>
            <th>Name</th>
            <th>Document</th>
            <th>FIle</th>
            <th>Updated</th>
            <th>Status</th>
        </tr>
        <slot name="extra-header"></slot>
        </thead>
        <tfoot></tfoot>
        <tbody>
        <tr v-for="valus in datalist" class="clickable">
            <td>{{valus.employee_id}}</td>
            <td>{{valus.employee_name}}</td>
            <td>{{valus.document_id}}</td>
            <td v-bind:title= valus.document_name >{{valus.document_name}}</td>
            <td>{{valus.created_at}}</td>
            <td>
                <span v-if="valus.status == 2 ">
                    <i class="fa fa-arrow-up" aria-hidden="true"></i>
                </span>
                <span v-if="valus.status == 1">
                    <i class="fa fa-check" aria-hidden="true"></i>
                </span>
                <span v-if="valus.status == 3">
                    <i class="fa fa-arrow-down" aria-hidden="true"></i>
                </span>

            </td>       
        </tr>
        </tbody>
    </table>
</template>

<script>
import EntitiesDocumentsTransferListTable from "./EntitiesDocumentsTransferListTable";
export default {
    name: "EntitiesDocumentsTransferDetailsTable",
    props: {
        extraClass: {
            type: String,
            default: "",
        },
        header: Array,
        list: Array,
    },
    data() {
        return {
            datalist: [],
            route:'',
        }
    },

    methods: {
        getUploadDocument(ids) {
            if(ids) {
                axios.get("/admin/isolved/api/document-transfer-details-list",{ params: {RecordID:ids}}).then(response => {
                   // this.loadedStatus = 1;
                    this.datalist = response.data; 
                    console.log(response.data);
                });
            } else {
                alert("Click the view button");
            }
        },

    },

    created() {
        var url = window.location.pathname;
        var id = url.substring(url.lastIndexOf('/') + 1);
        this.getUploadDocument(id);
    },
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
