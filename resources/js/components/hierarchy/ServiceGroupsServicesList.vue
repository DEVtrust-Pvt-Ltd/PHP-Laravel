<script>
import EntitiesSubList from "../EntitiesSubList";

export default {
    name: "ServiceGroupsServicesList",
    extends: EntitiesSubList,
    props:["groupId", ],

    data() {
        return {
            entity: "service_groups/" + this.groupId + "/services",
            fields: [
                "code",
                "name",
            ],
            list: [],

            url_hash: "#subscriptions",
            add_button_caption: "Add Subscription",
            modal_title: "Edit Subscription for " + this.$parent.entity.name,

        }
    },
}
</script>

<style scoped>

</style>
