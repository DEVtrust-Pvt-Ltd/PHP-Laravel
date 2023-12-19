<script>
import EntitiesSubList from "./EntitiesSubList";

export default {
    name: "EmployeeContactsList",
    extends: EntitiesSubList,
    props:["employeeId", ],

    data() {
        return {
            rootPath: "/admin/",
            rootApiPath: "/admin/api/",


            entity: "employees/" + this.employeeId + "/contacts",
            fields: [
                "first_name",
                "last_name",
                {
                    name:"relationship_id",
                    title:"Relationship",
                    type:"select",
                    list: {
                        url: '/admin/common/api/relationships'
                    }
                },
                "mobile_phone",
            ],
            list: [],

            url_hash: "#contacts",
            add_button_caption: "Add Emergency Contact",
            modal_title: "Edit Emergency Contact for " + this.$parent.employee.first_name + " " + this.$parent.employee.last_name,
        }
    },
}
</script>

<style scoped>

</style>
