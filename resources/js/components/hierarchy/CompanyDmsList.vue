<script>
import EntitiesSubList from "../EntitiesSubList";

export default {
    name: "CompanyDmsList",
    extends: EntitiesSubList,
    props:["companyId", ],

    data() {
        return {
            entity: "companies/" + this.companyId + "/dms",
            fields: [
                "name",
                "email",
                "phone",
                "area",
            ],
            list: [],

            url_hash: "#dms",
            add_button_caption: "Add Manager",
            modal_title: "Edit DM for " + this.$parent.company.name,
        }
    },
}
</script>

<style scoped>

</style>
