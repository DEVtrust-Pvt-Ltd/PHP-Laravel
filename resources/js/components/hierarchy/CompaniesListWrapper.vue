<template>
    <companies-list :alert="alert" :auth="auth">
    <template v-slot:extra-header>
        <companies-list-filter ref="companies_filter"></companies-list-filter>
    </template>
    </companies-list>
</template>

<script>
import CompaniesList from "./CompaniesList";
import CompaniesListFilter from "./CompaniesListFilter";
export default {
    name: "CompaniesListWrapper",
    components: {CompaniesList, CompaniesListFilter},
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
