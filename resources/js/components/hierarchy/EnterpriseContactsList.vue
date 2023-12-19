<script>
import EntitiesSubList from "../EntitiesSubList";

export default {
    name: "EnterpriseContactsList",
    extends: EntitiesSubList,
    props:["enterpriseId", ],

    data() {
        return {
            entity: "enterprises/" + this.enterpriseId + "/contacts",
            fields: [
                {
                    name:"contact_type",
                    type:"select",
                    list: {
                        url: '/admin/hierarchy/api/list/contact_types'
                    }
                },
                "first_name",
                "last_name",
                "email",
                "office_phone",
                "mobile_phone",
            ],
            list: [],

            url_hash: "#contacts",
            add_button_caption: "Add Enterprise Contact",
            modal_title: "Edit Contact for " + this.$parent.enterprise.name,
        }
    },
}
</script>

<style scoped>

</style>
