<script>
import EntitiesSubList from "../EntitiesSubList";

export default {
    name: "EnterpriseOptionsList",
    extends: EntitiesSubList,
    props:["enterpriseId", ],

    data() {
        return {
            entity: "enterprises/" + this.enterpriseId + "/options",
            fields: [
                "option_name",
                "option_value",
            ],
            list: [],

            url_hash: "#options",
            add_button_caption: "Add Enterprise Option",
            modal_title: "Edit Option for " + this.$parent.enterprise.name,
        }
    },
}
</script>

<style scoped>

</style>
