<script>
import EntitiesMainList from "./EntitiesMainList";

export default {
    name: "EmployeesList",
    extends: EntitiesMainList,

    props: {
        companyId: {
            default: null,
        }
    },

    data() {
        return {
            pageTitle: "Employees",
            addItemTitle: "Add New Employee",
            entity: "employees",

            fields:[
                "employee_number",
                "first_name",
                "last_name",
            ],

            rootPath: "/admin/",
            rootApiPath: "/admin/api/",

            allowLoading: false,
            allowCreate: false,
        }
    },

    methods: {
        noDelete(item) {
            return !this.auth.mayDelete;
        },

        addItem() {
            document.location.href = this.rootPath + this.entity + "/new?companyId=" + this.companyId;
        },

    },
    watch: {
        companyId(value) {
            console.log("EmployeesList Company Id", value);

            if("" !== value) {
                this.queryParams.companyId = value;
                this.allowLoading = true;
                this.allowCreate = true;
                this.loadData();
            } else {
                this.allowLoading = false;
                this.allowCreate = false;
            }


        }
    }
}
</script>

<style scoped>

</style>
