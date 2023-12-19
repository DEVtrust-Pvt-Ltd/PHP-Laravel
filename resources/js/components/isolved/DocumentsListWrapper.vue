<template>
    <documents-list :alert="alert" :auth="auth">
    <template v-slot:extra-header>
        <documents-list-filter ref="documents_filter"></documents-list-filter>        
    </template>
    </documents-list>
</template>

<script>
import DocumentsListFilter from "./DocumentsListFilter";
import DocumentsList from "./DocumentsList";
export default {
    name: "DocumentsListWrapper",
    components: {DocumentsList, DocumentsListFilter},
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
