<script>
import EntitiesMainList from "../EntitiesMainList";

export default {
    name: "ServiceGroupsList",
    extends: EntitiesMainList,

    data() {
        return {
            pageTitle: "Services",
            addItemTitle: "Add New Service",
            entity: "service_groups",

            fields:[
                "code",
                "name",
            ]
        }
    },
}
</script>

<style scoped>
</style>
