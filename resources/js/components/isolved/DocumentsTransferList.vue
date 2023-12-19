<script>
import IsolvedTransferMainList from "../IsolvedTransferMainList";

export default {
    name: "DocumentsTransferList",
    extends: IsolvedTransferMainList,

    props: {
        companyId: {
            default: null,
        }
    },


    data() {
        return {
            pageTitle: "Documents Transfer",
            addItemTitle: "ADD",
            entity: "document-transfer",
            searchEmp: '',
            originalList: [],
            filter: {},

            fields:[  
               "destination_companies", 
               "source_companies",                
                "source_enterprise",
                "destination_enterprise", 
                "source_enterprises",
                "source_company",
                "destination_company",
                "destination_enterprises",
                "total_employees",
                "total_documents",
                "state",
                "status",
            ],
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
                        return "" + item.name === "" + this.filter[key];
                    });    
                } else {
                   switch(key) {
                    case "document":
                        const _l = this.filter[key].trim().toLowerCase();
                      
                        if(1) { //source_enterprise destination_enterprise destination_companies source_companies
                            filtered = filtered.filter(item => {
                                return 0 ===item.source_enterprises.toLowerCase().indexOf(_l)
                                    //|| 0 <= item.source_enterprise.toLowerCase().indexOf(_l)
                                    //|| 0 <= item.source_companies.toLowerCase().indexOf(_l)
                                    || 0 <= item.source_company.toLowerCase().indexOf(_l)
                                    //|| 0 <= item.destination_enterprise.toLowerCase().indexOf(_l)
                                    //|| 0 <= item.destination_companies.toLowerCase().indexOf(_l)
                                    || 0 <= item.destination_company.toLowerCase().indexOf(_l)
                                    || 0 <= item.destination_enterprises.toLowerCase().indexOf(_l);
                                    
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


    