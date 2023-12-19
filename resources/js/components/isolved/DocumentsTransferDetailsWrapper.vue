<template>
    <documents-transfer-details :alert="alert" :auth="auth">
    <template v-slot:extra-header>
        <documents-list-filter ref="documents_filter"></documents-list-filter>        
    </template>
    </documents-transfer-details>
</template>

<script>
import DocumentsListFilter from "./DocumentsListFilter";
import DocumentsTransferDetails from "./DocumentsTransferDetails";
export default {
    name: "DocumentsTransferDetailsWrapper",
    components: {DocumentsTransferDetails, DocumentsListFilter},
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


