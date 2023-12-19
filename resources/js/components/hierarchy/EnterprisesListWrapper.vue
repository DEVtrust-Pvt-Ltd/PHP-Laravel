<template>
    <enterprises-list :alert="alert" :auth="auth">
    <template v-slot:extra-header>
        <enterprises-list-filter ref="enterprises_filter"></enterprises-list-filter>
    </template>
    </enterprises-list>
</template>

<script>
import EnterprisesListFilter from "./EnterprisesListFilter";
import EnterprisesList from "./EnterprisesList";
export default {
    name: "EnterprisesListWrapper",
    components: {EnterprisesList, EnterprisesListFilter},
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
