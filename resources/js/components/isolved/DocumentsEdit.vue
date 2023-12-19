<template>
<div class="list-wrapper" :class="extraClass">
    <div class="card">
        <div class="card-header">
            <a href="/admin/isolved/documents">Campany list</a> &gt; {{pageTitle}}
        </div>

        <div class="card-body">
            <div class="form-group row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-2" >
                            Enterprices
                        </div>
                        <div class="col-sm-10">                
                            <select v-on:input="getComapny($event)" id="enterpriseid" ref="select_el" style="width: 80%; padding: 10px;
                            ">
                                <option selected ="selected" v-bind:value='-1' >Select Enterprices</option>
                                <option v-for="values in enterprise" v-bind:value='values.iSolvedClient' v-text="values.name"  >{{values.name}}</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-2 ">
                            Company
                        </div> 
                        <div class="col-sm-10">                
                            <select ref="select_el" id="companyid"  style="width: 80%; padding: 10px;
                            ">
                                <option selected ="selected" v-bind:value='-1' > Select Comapny </option>
                                <option v-for="values in company" v-bind:value='values.legalCode' v-text="values.legalName"  >{{values.legalName}}</option>
                            </select>
                        </div>
                    </div>   
           
                <div class="d-flex justify-content-end mt-3 pr-4">
                    <button v-on:click="downloadDocument()" class="btn btn-primary btn-lg">
                        {{ isSaving ? 'Exporting...' : 'Export'}}
                    </button>
                </div> 
                <slot name="after-table"></slot>
                    <h1 class="loading" v-if="0 === loadedStatus" ><img src="/img/loading.gif" /></h1>
                </div> 
            </div> 
        </div>


    </div>

</div>

</template>

<script>

export default {
    name: "DocumentsEdit",
    components: {},
    props: {
        auth: {
            type: Object,
            default: {
                isMaster: false,
            }
        }
    },
    data() {
        return {
            item: {
                id: null
            },

            tabs: {
                general: {
                    title: "Employee Documents",
                    active: true,
                    disabled:false,
                    titleAddOn: "Selecte Comapny",
                },
            },

            currentTab: "general",          

            document: {
                id: 0,
                concepts: [],
            },
            enterprise: [],
            company: [],
            companyid: null,
            enterpriseid: null,

            allowLoading: true,
            loadedStatus: 1,

        }
    },
    methods: {
        getEnterprise(){            
            axios.get("/admin/isolved/get_enterprise").then(response => {                
                this.enterprise = response.data; 
                //console.log(response.data);
            });
        },
        getComapny(event) {
            if(!this.allowLoading) return;
            this.loadedStatus = 0;
            if(event.target.value){
                axios.get("/admin/isolved/company",{ params: {types:'companys',enterprise_id:event.target.value}}).then(response => {
                    this.loadedStatus = 1;
                    this.company = response.data; 
                    //console.log(response.data);
                });
            } else {
                alert("Please setect the Enterprise");
            }

        },
        downloadDocument() {
           const  Comp_id = document.getElementById('companyid').value;
           const  EnterpricesId = document.getElementById('enterpriseid').value;
            if (EnterpricesId != -1 && Comp_id != -1) {                
            alert("You have successfully schedule the document downloading.");
            if(!this.allowLoading) return;
            this.loadedStatus = 0;
                axios.get("/admin/isolved/GetEmployee", { params: {types:'employees',compid:Comp_id, enterprisescode:EnterpricesId}}).then(response => {
                    // this.enterprise = response.data.entity; 
                        this.loadedStatus = 1;               
                       alert("Document download successfully.");
                        // console.log(response.data);
                });
            } else {
                alert("Please setect the Enterprise & Company");
            }            
        },
    },
    created() {
        this.getEnterprise();
    },

    computed: {
        pageTitle() {
            let title = "";
            if(!this.document.id) {
                title += " Document Download of Employee ";
            } 

            if(this.tabs[this.currentTab].titleAddOn) {
                title += " > " + this.tabs[this.currentTab].titleAddOn;
            }

            return title;
        },

    },

   
}
</script>

<style scoped>
h1.loading {
    text-align: center;
}
</style>
