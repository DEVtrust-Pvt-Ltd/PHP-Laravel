<script>
import EntitiesSubList from "./EntitiesSubList";

export default {
    name: "EmployeeCertificationsList",
    extends: EntitiesSubList,
    props:["employeeId", ],

    data() {
        return {
            rootPath: "/admin/",
            rootApiPath: "/admin/api/",


            entity: "employees/" + this.employeeId + "/certifications",
            fields: [
                "certification",
                "authority",
                {
                    name:"effective_date",
                    type:"date",
                },
                "certification_id",
                {
                    name:"expiration_date",
                    type:"date",
                },

            ],
            list: [],

            url_hash: "#certifications",
            add_button_caption: "Add Certification",
            modal_title: "Edit Certification for " + this.$parent.employee.first_name + " " + this.$parent.employee.last_name,
        }
    },
}
</script>

<style scoped>

</style>
