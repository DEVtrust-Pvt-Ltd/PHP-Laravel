<template>
<div class="list-wrapper" :class="extraClass">
    <div class="card">
        <div class="card-header">
            <span id="pageTitle">{{pageTitle}}  </span>
            <span class="float-right">
                <slot name="title-slot" ></slot>

<a class="btn btn-info btn-sm " id="adddocument" v-on:click="adddocument" v-if="auth.mayCreate" :disabled="!allowCreate">Upload Document </a>

<a class="btn btn-info btn-sm addItemTitle " id="addItemTitle" v-on:click="addItem" v-if="auth.mayCreate" :disabled="!allowCreate">{{addItemTitle}} </a>

            </span>
        </div>

        <div class="card-body">
            <div v-if="'' !== alert" class="alert alert-success" role="alert">
                {{ alert }}
            </div>

            <slot name="before-table"></slot>

            <entities-documents-list-table :extra-class="extraClass" :header="header" :list="renderedList" ref="entitiesdocuments_list" >
                <template v-slot:extra-header><slot name="extra-header"></slot></template>
            </entities-documents-list-table>

            <slot name="after-table"></slot>
            <h1 class="loading" v-if="0 === loadedStatus"><img src="/img/loading.gif" /></h1>

        </div>
        
    </div>
    

</div>

</template>

<script>
import EntitiesDocumentsList from "./EntitiesDocumentsList";
import EntitiesDocumentsListTable from "./EntitiesDocumentsListTable";

export default {
    name: "IsolvedMainList",
    components:{EntitiesDocumentsListTable},
    extends: EntitiesDocumentsList,
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
        }
    },
    mounted() {;
        // const _list = this.$refs.entities_list;
        // _list.$on("click:view", (id) => {
        //     this.editItem(id);
        // });
        // _list.$on("click:row", (item, idx, event) => {
        //     this.editItem(item, idx, event);
        // });
        // _list.$on("click:delete", (item, idx, event) => {
        //     this.deleteItem(item, idx, event);
        // });
        // EventBus.$on('DATA_PUBLISHED', (payload) => {
        //     this.updateData(payload)
        // });
        
    }
   
}

</script>

<style scoped>

</style>
