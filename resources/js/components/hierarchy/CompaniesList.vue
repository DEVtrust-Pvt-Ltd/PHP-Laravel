<script>
import statesList from "../../tools/states";
import EntitiesMainList from "../EntitiesMainList";

export default {
    name: "CompaniesList",
    extends: EntitiesMainList,

    mounted() {

        this.$on("data:loaded", list => {
            this.originalList = list;
        });

        this.$parent.$refs.companies_filter.$on("filter:enterprise", v => {
            this.filter.enterprise = v;
            this.filterList()
        });

        this.$parent.$refs.companies_filter.$on("filter:company", v => {
            this.filter.company = v;
            this.filterList()
        });
    },

    data() {
        return {
            pageTitle: "Companies",
            addItemTitle: "Add New Company",
            entity: "companies",

            originalList: [],

            filter: {},

            fields:[
                {
                    name: "enterprise_id",
                    title: "Enterprise",
                    type: "select",
                    list: {
                        url: "/admin/hierarchy/api/list/enterprises",
                    }
                },
                "code",
                "name",
                {
                    name: "state",
                    type: "select",
                    list: {
                        data: statesList.map(x => {
                            return {
                                value: x.abbreviation,
                                title: x.name,
                            }
                        }),
                    }
                },

            ]
        }
    },

    methods: {
        filterList() {
            let filtered = this.originalList;
            for(const key in this.filter) {
                if(!this.filter[key]) continue;

                switch(key) {
                    case "enterprise":
                        filtered = filtered.filter(item => {
                            return "" + item.enterprise_id === "" + this.filter[key];
                        });
                        break;

                    case "company":
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
