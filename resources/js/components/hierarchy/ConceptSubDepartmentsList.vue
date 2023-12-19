<script>
import EntitiesSubList from "../EntitiesSubList";

export default {
    name: "ConceptSubDepartmentsList",
    extends: EntitiesSubList,
    props:["conceptId", ],

    data() {
        return {
            entity: "concepts/" + this.conceptId + "/sub_departments",
            fields: [
                {
                    name: "department_id",
                    title: "Department",
                    type: "select",
                    list: {
                        url: "/admin/hierarchy/api/list/departments/" + this.conceptId,
                    }
                },
                {
                    name: "name",
                    title: "Sub Department",
                },
                "isolved_code",
            ],
            formFields: [
                {
                    name: "department_id",
                    title: "Department",
                    type: "select",
                    list: {
                        url: "/admin/hierarchy/api/list/departments/" + this.conceptId,
                    }
                },
                {
                    name: "name",
                    title: "Sub Department",
                },
                "isolved_code",
                "snag_code",
                "talent_reef_code",
                "ef_code",
                "misc1",
                "misc2",
                "misc3",
            ],
            list: [],

            url_hash: "#sub_departments",
            add_button_caption: "Add Sub Department",
            modal_title: "Edit Sub Department for " + this.$parent.concept.name,

        }
    },
}
</script>

<style scoped>

</style>
