<script>
import EntitiesSubList from "../EntitiesSubList";

export default {
    name: "ConceptDepartmentsList",
    extends: EntitiesSubList,
    props:["conceptId", ],

    data() {
        return {
            entity: "concepts/" + this.conceptId + "/departments",
            fields: [
                {
                    name: "name",
                    title: "Department",
                },
                "isolved_code",
            ],
            formFields: [
                {
                    name: "name",
                    title: "Department",
                },
                "isolved_code",

                "misc1",
                "misc2",
                "misc3",
            ],
            list: [],

            url_hash: "#departments",
            add_button_caption: "Add Department",
            modal_title: "Edit Department for " + this.$parent.concept.name,

        }
    },
}
</script>

<style scoped>

</style>
