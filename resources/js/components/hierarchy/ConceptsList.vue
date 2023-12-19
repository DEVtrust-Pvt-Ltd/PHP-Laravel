<script>
import EntitiesMainList from "../EntitiesMainList";

export default {
    name: "ConceptsList",
    extends: EntitiesMainList,

    data() {
        return {
            pageTitle: "Concepts",
            addItemTitle: "Add New Concept",
            entity: "concepts",

            fields:[
                "code",
                "name",
                {
                    name: "avgEmployees",
                    title: "Avg Employees"
                },
            ]
        }
    },
}
</script>

<style scoped>
</style>
