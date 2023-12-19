
<template>
    <div class="list-wrapper" :class="extraClass">
        <div class="card-body">
            <div class="form-group row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-2" >
                            Enterprices
                        </div>
                        <div class="col-sm-10">                
                            <select v-on:input="getComapny($event)" id="enterprise_id"  ref="select_el" style="width: 80%; padding: 10px;
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
                            <select ref="select_el" id="company_id"  v-on:input="getEmployees($event)"  style="width: 80%; padding: 10px;
                            ">
                                <option selected ="selected" v-bind:value='-1' > Select Comapny </option>
                                <option v-for="values in company" v-bind:value='values.legalCode' v-text="values.legalName" >{{values.legalName}}</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <!-- <div class="row">
                        <div class="col-sm-2 ">
                            Employees
                        </div> 
                        <div class="col-sm-10">                
                            <select ref="select_el" id="EmployeeId"  style="width: 80%; padding: 10px;
                            ">
                                <option selected ="selected" v-bind:value='-1' > Select Employee </option>
                                <option v-for="values in employees" v-bind:value='values.id' v-text="values.name"  >{{values.name}}</option>
                            </select>
                        </div>
                    </div> 
                    <br>
                     -->
                     
                    <div class="row">
                        <div class="col-sm-2 ">                            
                           Document Upload
                        </div> 
                        <div class="col-sm-10">       
                           
            <div class="custom-file">
                <input type="file" id="file" ref="file" class="custom-file-input" multiple="multiple">
                <label class="custom-file-label" style="width: 80%;" for="file">Zip File Choose</label>
            </div>
                             
                        </div>

                    </div>    
           
                <div class="d-flex justify-content-end mt-3 pr-4">
                    <button v-on:click="uploadDocument()" class="btn btn-primary btn-lg">
                        {{ isSaving ? 'Uploading...' : 'Upload'}}
                    </button>
                </div> 
                <slot name="after-table"></slot>
                    <h1 class="loading" v-if="0 === loadedStatus" ><img src="/img/loading.gif" /></h1>
                </div> 
            </div> 
        </div>
    </div>
</template>

<script>
export default {
    name: "EnterpriceCompany",
    components: {},
    data() {
        return {
            enterprise: [],
            company: [],
            employees: [],
            allowLoading: true,
            loadedStatus: 1,
            enterprise_id: '',
            company_id:'',
            EmployeeId:'',
            datalist: {},
            //file: '',
        }

    },
        methods: {
            getEnterprise(){            
                axios.get("/admin/isolved/get_enterprise").then(response => {                
                    this.enterprise = response.data; 
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
            getEmployees(event) {
                // if(!this.allowLoading) return;
                // this.loadedStatus = 0;
                // var EnterpricesId = enterprise_id.value;
                // var Comp_id = company_id.value;
                // if (EnterpricesId != -1 && Comp_id != -1) {                    
                //     axios.get("/admin/isolved/employeelist", { params: {types:'employees',compid:Comp_id, enterprisescode:EnterpricesId}}).then(response => {
                //         this.loadedStatus = 1;
                //         this.employees = response.data; 
                //         //console.log(response);
                //     });
                // } else {
                //     alert("Please setect the Enterprise & Comapny");
                // }
            },
            uploadDocument() {
                var EnterpricesId = enterprise_id.value;
                var Comp_id = company_id.value;
               // var emp_id = EmployeeId.value;
                
                let formData = new FormData();
                console.log(this.$refs.file.files[0]);
                if(this.$refs.file.files[0]){
                    this.file = this.$refs.file.files[0];
                    formData.append('file', this.file);
                } else {                    
                    alert("please choose the file name");
                    return false;
                }
                formData.append('types', 'uploadfile');
                formData.append('compid', Comp_id);
                formData.append('enterprisescode', EnterpricesId);
               // formData.append('emp_id', emp_id);
                
            if (EnterpricesId != -1 && Comp_id != -1 ) {  
                
                alert("Your Document Uploading.");   
                axios.post("/admin/isolved/uploaddocument",formData ).then(response => {
                    if(parseInt(response) == -404){
                        alert("500 internal server code problem please try again.");
                        return false;
                    } else if (parseInt(response) == 1) {
                        alert("Your Document Upload.");
                    } else if(parseInt(response) == -401) {
                        alert("Network issue");
                        return false;
                    } else if(parseInt(response) == -405) {
                        alert("Please choose the employee document");
                        return false;
                    }
                    
                });  
            } else {
                alert("Please setect the Enterprise & Comapny and employee");
                return false;
            }

            }

        },
    created() {
        this.getEnterprise();
    },
}
</script>