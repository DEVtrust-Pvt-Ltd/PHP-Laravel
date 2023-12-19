<template>
<div class="list-wrapper" :class="extraClass">
    <div class="card">
        <div class="card-header">
            <span id="pageTitle">{{pageTitle}}  </span>
            <span class="float-right">
                <slot name="title-slot" ></slot>
                <a class="btn btn-info btn-sm" v-on:click="addItem" v-if="auth.mayCreate" :disabled="!allowCreate">{{addItemTitle}} </a>
            </span>
        </div>

        <div class="card-body">
            <div v-if="'' !== alert" class="alert alert-success" role="alert">
                {{ alert }}
            </div>

            <slot name="before-table"></slot>

            <entities-documents-transfer-details-table :extra-class="extraClass" :header="header" :list="renderedList" ref="entitiesdocuments_list" >
                <template v-slot:extra-header><slot name="extra-header"></slot></template>
            </entities-documents-transfer-details-table>

            <slot name="after-table"></slot>
            <h1 class="loading" v-if="0 === loadedStatus"><img src="/img/loading.gif" /></h1>

        </div>        
    </div>
    

</div>

</template>

<script>
import EntitiesDocumentsTransferDetailsTable from "../EntitiesDocumentsTransferDetailsTable";

export default {
    name: "DocumentsTransferDetails",
    components:{EntitiesDocumentsTransferDetailsTable},
 
    props: {
        auth: {
            type: Object,
            default: {
                mayCreate: true,
                mayEdit: true,
                mayDelete: true,
            }
        },
        //dataClick:Number,    
    },
    data() {
        return {
            allowCreate: true,
            pageTitle: "Documents Transfer Details",
            addItemTitle: 'Back',
            entity: 'documents-transfer-list',
             rootPath: "/admin/isolved/",
            searchEmp: '',
            alert:"",
            allowLoading: true,
            loadedStatus: 1,
    
        }
    },
    methods: {
       // 'admin/isolved/documents-transfer-list'
       addItem() {
            window.location.href = this.rootPath+this.entity;
       },
        
    }

}
</script>
<style scoped>
</style>



    