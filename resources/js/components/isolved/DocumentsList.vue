<script>
//import EntitiesMainList from "../EntitiesMainList";
import IsolvedMainList from "../IsolvedMainList";

export default {
    //name: "EnterprisesList",
    name: "DocumentsList",
    extends: IsolvedMainList,

    props: {
        companyId: {
            default: null,
        }
    },


    data() {
        return {
            pageTitle: "Documents",
            addItemTitle: "New Documents Process",
            entity: "document",
            searchEmp: '',

            originalList: [],

            filter: {},

            fields:[
                "enterprises_name",
                "company_code",
                "total_employees",
                "datetime",
                "status",
                //"Action"
                // {
                //     name: "Action",
                //     type: "boolean",
                // },
            ],

            //rootPath: "/admin/",
            //rootApiPath: "/admin/api/",

            //allowLoading: false,
            //allowCreate: false,
        }
    },

    mounted() {

        this.$on("data:loaded", list => {            
            this.originalList = list;                
        });

        this.$parent.$refs.documents_filter.$on("filter:document", v => {            
           this.filter.document = v;           
            this.filterList();               
                   
        }); 


    },

    methods: {
        filterList() {
            let filtered = this.originalList;  
             let searchEmp = $("#pageTitle a").html(); // search employee when you click the details button
                if(searchEmp === undefined) {
                    searchEmp = '';
                } 
            for(const key in this.filter) {
                if(!this.filter[key]) continue;

               
               if(String(searchEmp)){
                    filtered = filtered.filter(item => {
                        //alert(this.filter[key]);
                        return "" + item.name === "" + this.filter[key];
                    });    
                } else {
                   switch(key) {
                    case "document":
                        const _l = this.filter[key].trim().toLowerCase();
                      
                        if(parseInt(_l)){
                            filtered = filtered.filter(item => {
                                return "" + item.total_employees === "" + this.filter[key];
                            });
                        } else {
                            filtered = filtered.filter(item => {
                                return 0 ===item.enterprises_name.toLowerCase().indexOf(_l)
                                    || 0 <= item.company_code.toLowerCase().indexOf(_l);
                            });
                        }                        
                        break;
                    }
                }
               
            }
            if(String(searchEmp) == ''){
                this.list = filtered;
            }
        },


        noDelete(item) {
            return !this.auth.mayDelete;
        },
    }
}
</script>

<style scoped>

</style>
