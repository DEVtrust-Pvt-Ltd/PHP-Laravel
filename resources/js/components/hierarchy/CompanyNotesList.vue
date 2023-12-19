<script>
import EntitiesSubList from "../EntitiesSubList";

export default {
    name: "CompanyNotesList",
    extends: EntitiesSubList,
    props:["companyId", ],

    data() {
        return {
            entity: "companies/" + this.companyId + "/notes",
            fields: [
                {
                    name: "note",
                    type: "longtext",
                },
            ],
            list: [],

            url_hash: "#notes",

            add_button_caption: "Add Note",
            modal_title: "Edit Company Note for " + this.$parent.company.name,
        }
    },

}
</script>

<style scoped>

</style>
