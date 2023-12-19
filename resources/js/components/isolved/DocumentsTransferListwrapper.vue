<template>
    <documents-transfer-list :alert="alert" :auth="auth">
    <template v-slot:extra-header>
        <documents-list-filter ref="documents_filter"></documents-list-filter>        
    </template>
    </documents-transfer-list>
</template>

<script>
import DocumentsListFilter from "./DocumentsListFilter";
import DocumentsTransferList from "./DocumentsTransferList";
export default {
    name: "DocumentsTransferListwrapper",
    components: {DocumentsTransferList, DocumentsListFilter},
    props: {
        alert: {
            type: String,
            default: '',
        },
        auth: {
            type: Object,
            default: {
                mayCreate: true,
                mayEdit: true,
                mayDelete: true,
            }
        }
    }
}
</script>

<style scoped>

</style>


