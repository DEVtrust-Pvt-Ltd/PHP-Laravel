<template>
<div class="main-employees-wrapper">
    <employees-list :alert="alert" :auth="auth" :company-id="companyId" ref="employees_list">
        <template v-slot:extra-header>
            <employees-list-filter ref="employees_filter"></employees-list-filter>
        </template>
        <template v-slot:title-slot>
            <div class="company-select-wrapper">
                <div class="form-group row company-select-form-group">
                    <label for="companySelect" class="col-sm-3 col-form-label col-form-label-sm">Company :</label>
                    <div class="col-sm-8">
                        <select id="companySelect" v-model="companyId" class="form-control form-control-sm">
                            <option value="">Select Company</option>
                            <option v-for="c in companiesList" :value="c.id">{{c.code}} "{{c.name}}"</option>
                        </select>
                    </div>
                </div>
            </div>
        </template>
        <template v-slot:after-table>
            <h4 class="text-center" v-if="'' === companyId">Please Select Company</h4>
            <paginator v-if="'' !== companyId"></paginator>
        </template>
    </employees-list>

</div>
</template>

<script>
import EmployeesList from "./EmployeesList";
import EmployeesListFilter from "./EmployeesListFilter";
import SelectField from "./form-fields/SelectField";
import Paginator from "./tools/Paginator";
export default {
    name: "EmployeesListWrapper",
    components: {Paginator, SelectField, EmployeesList, EmployeesListFilter},
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
    },
    data() {
        return {
            companyId: "",
            companiesList: [],

            perPage: 25,
            pageNumber: 1,
        }
    },

    async mounted() {
        this.companiesList = (await axios.get('/admin/common/api/companies')).data;
    },

    computed: {
    }
}
</script>

<style scoped>
.company-select-wrapper {
    display: inline-block;
}

.company-select-form-group {
    margin-bottom: 0;
}
</style>
