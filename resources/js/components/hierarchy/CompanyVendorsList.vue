<script>
import EntitiesSubList from "../EntitiesSubList";

export default {
    name: "CompanyVendorsList",
    extends: EntitiesSubList,
    props:["companyId", ],

    data() {
        return {
            entity: "companies/" + this.companyId + "/vendors",
            fields: [
                {
                    name: "vendor_code",
                    title: "Code",
                },
                {
                    name: "vendor_name",
                    title: "Name",
                },
                {
                    name: "vendor_intacct_id",
                    title: "Intacct ID",
                },
            ],
            list: [],

            url_hash: "#vendors",

            add_button_caption: "Add Vendor",
            modal_title: "Edit Vendor for " + this.$parent.company.name,
        }
    },

}
</script>

<style scoped>

</style>
