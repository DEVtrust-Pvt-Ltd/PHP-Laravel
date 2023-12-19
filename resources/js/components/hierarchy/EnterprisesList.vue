<script>
import EntitiesMainList from "../EntitiesMainList";

export default {
    name: "EnterprisesList",
    extends: EntitiesMainList,

    data() {
        return {
            pageTitle: "Enterprises",
            addItemTitle: "Add New Enterprise",
            entity: "enterprises",

            originalList: [],

            filter: {},

            fields:[
                "code",
                "name",
                {
                    name: "active",
                    type: "boolean",
                },
            ]
        }
    },

    mounted() {

        this.$on("data:loaded", list => {
            this.originalList = list;
        });

        this.$parent.$refs.enterprises_filter.$on("filter:enterprise", v => {
            this.filter.enterprise = v;
            this.filterList()
        });

    },

    methods: {
        filterList() {
            let filtered = this.originalList;
            for(const key in this.filter) {
                if(!this.filter[key]) continue;

                switch(key) {
                    case "enterprise":
                        const _l = this.filter[key].trim().toLowerCase();
                        filtered = filtered.filter(item => {
                            return 0 ===item.code.toLowerCase().indexOf(_l)
                                || 0 <= item.name.toLowerCase().indexOf(_l);
                        });
                        break;
                }
            }
            this.list = filtered;
        },


        noDelete(item) {
            return !this.auth.mayDelete;
        },
    }
}
</script>

<style scoped>

</style>
