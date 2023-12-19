<template>

<table class="main-list-table table table-hover" :class="extraClass">
    <thead class="thead-light">
    <tr>
        <th>Job Id</th>
        <th>Source</th>
        <th>Destination</th>
        <th>E/D</th>
        <th>Created</th>
        <th>Updated</th>
        <th>State</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <slot name="extra-header"></slot>
    </thead>
    <tfoot></tfoot>
    <tbody>
    <tr v-for="(item, idx) in list" class="clickable">
        <td>{{idx+1}}</td>
        <td>
            <span v-if="item.item.source_enterprise">
                <span>{{item.item.source_companies}}</span><br>
                <span>{{item.item.source_enterprise}}</span>
            </span>
            <span v-else>{{item.item.source_enterprises}}</span>
        </td>
       <td>
            <span v-if="item.item.destination_enterprise">
                <span>{{item.item.destination_companies}}</span><br>
                <span>{{item.item.destination_enterprise}}</span>
            </span>
            <span v-else>{{item.item.destination_enterprises}}</span>
        </td> 
        <td><span v-if="item.item.total_employees == null">0</span><span v-else>{{item.item.total_employees}}</span>/<span v-if="item.item.total_documents == null">0</span><span v-else>{{item.item.total_documents}}</span></td>
        <td>{{item.item.created_at}}</td>
        <td>{{item.item.updated_at}}</td>
        <td>
            
            <span v-if="item.item.state == 3">
                <i class="fa fa-arrow-up" aria-hidden="true"></i>
                <i class="fa fa-arrow-down" aria-hidden="true"></i>
            </span>
            <span v-if="item.item.state == 1 ">
                <i class="fa fa-arrow-up" aria-hidden="true"></i>
                
            </span>
            <span v-if="item.item.state == 2">
                <i class="fa fa-arrow-down" aria-hidden="true"></i>
            </span>

        </td>
        <td>
            
            <span v-if="item.item.status == 1" class="text">
                Completed
            </span>
            <span v-if="item.item.status == 2" class="text">
                In-Progress 
            </span>
            <span v-if="item.item.status == 4" class="text">
                Errored 
            </span>
            <span v-if="item.item.status == 3" class="text">
                Terminated 
            </span>
            <span v-if="item.item.status == 5" class="text">
                Document not available <br>at SFTP.
            </span>

        </td>
        <td>
            <a class="btn btn-primary btn-sm " @click.stop="editItem(item.item.id)" v-if="!item.noEdit">
                <i class="fa fa-users" aria-hidden="true"></i>               
            </a>

            <a class="btn btn-danger btn-sm " @click.stop="viewdetails(item.item, idx, $event)" v-if="!item.noDelete"> <i class="fa fa-ban" aria-hidden="true"></i></a>

        </td>
    </tr>
    </tbody>
</table>
</template>

<script>
export default {
    name: "EntitiesDocumentsTransferListTable",
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
            rootPath: "/admin/isolved/",
            rootApiPath: "/admin/isolved/api/",
        }
    },

    methods: {
        rowClicked(item, idx, event) {
            this.$emit("click:row", item, idx, event);
        },

        // editItem(ids) {
        //     this.$emit("click:edit", ids);
        // },

        editItem(ids) {
            event.cancelBubble = true;
            document.location.href = this.rootPath + "documents-transfer-details/"+ids;
        },



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
