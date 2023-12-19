<template>
<div class="container-fluid">
    <div class="form-steps">
        <span class="step-childs font-bold">
            <span class="steps active-v">1</span>Add file
        </span>
        
        <span class="step-childs">
            <span class="steps ">2</span>Validate file
        </span>

        <span class="step-childs">
            <span class="steps">3</span>Review & Import
        </span>
    </div>
    <br>
        <div class="row">
            <div class='col-sm-6'>
                <div class="form-group">
                <label for="from_date" class="font-weight-bold">ENTERPRISE:</label>
                    <multiselect v-model="enterpriseValue" :options="enterpriseList" placeholder="Select" label="code" track-by="code" @input="getCompanyId"></multiselect>
                </div>
            </div>
            <div class='col-sm-6'>
                <div class="form-group">
                <label for="from_date" class="font-weight-bold">COMPANY:</label>
                    <multiselect v-model="companyValue" :options="companyList" placeholder="Select" label="name" track-by="name"></multiselect>
                </div>
            </div>
        </div>      
        <div class="row">
            <div class="col-sm-6"> 
                 <label class="font-weight-bold">IMPORT FILE</label>
                    <input type="file" id="file" ref="file" v-on:change="handleFileUpload()"/>
            </div>    
        </div><br>
        <div class="row">
             <div class="col-sm-3" style="float: right;margin-left: 600px">  
                <button v-on:click="submitFile()"  class="btn btn-primary">NEXT</button>
             </div><br><br>   
             <div class="col-sm-3"> <h1 class="loading" v-if="1 === loadedStatus"><img src="/img/loading.gif" /></h1></div>
        </div>
</div>
</template>

<script>
import Multiselect from 'vue-multiselect';
export default { 

  components: { Multiselect },
  
  data() {
    return {
        enterpriseValue: null,
        companyValue: null,
        enterpriseId: null,
        enterpriseList: [],
        companyList: [],
        file: '',  
        loadedStatus: 0,
    }
  },
  methods: {
                /*
                    Handles a change on the file upload
                */
                handleFileUpload(){
                    this.file = this.$refs.file.files[0];
                },
                 /*
                    Submits the file to the server
                */
                submitFile(){
                     this.loadedStatus = 1;
                     if(this.enterpriseValue && this.companyValue){
                        if(this.file){
                            const config = {
                                                headers: { 'content-type': 'multipart/form-data' }
                                        }
                            let formData = new FormData();
                            formData.append('file', this.file);
                            formData.append('enterprise_id', this.enterpriseValue.id);
                            formData.append('company_id', this.companyValue.id);

                            axios.post('/admin/validate_mbm_gl_import', formData).then(res => {
                                if(res.data.status){
                                    this.resetField();  
                                    const data = toast.fire({
                                                                icon: 'success',
                                                                title: res.data.message
                                                            })
                                    if(data){
                                         window.location.href = '/admin/validate_mbm_import_list';
                                    }
                                } 
                                else {
                                    toast.fire({
                                            icon: 'error',
                                            title: res.data.message
                                    })
                                    this.loadedStatus = 0; 
                                }   
                            })
                        }
                        else {
                                toast.fire({
                                    icon: 'error',
                                    title: 'please select file for import'
                                })
                                this.loadedStatus = 0; 
                        }    
                    }
                    else {
                           this.loadedStatus = 0;
                            if(this.enterpriseValue){
                                toast.fire({
                                    icon: 'error',
                                    title: 'please select company'
                                })
                            }
                            else if(this.companyValue){
                                toast.fire({
                                    icon: 'error',
                                    title: 'please select enterprise '
                                })
                            } 
                            else {
                                 toast.fire({
                                    icon: 'error',
                                    title: 'please select enterprise and company both'
                                })
                            } 
                    }
                },

                // Get Enterprise List
                getEnterprise() {
                    axios.get('/admin/get_enterprise_list').then(res => {
                        this.enterpriseList = res.data.data
                    })
                    .catch(error=>{
                        toast.fire({
                                icon: 'error',
                                title: error.data.message
                        })
                    });    
                },

                //Get Company Id
                getCompanyId() {
                    if(this.enterpriseValue){
                        this.enterpriseId = this.enterpriseValue.id;
                        axios.get('/admin/get_enterprise_company_list/'+this.enterpriseId).then(res => {
                            this.companyList = res.data.data
                        })
                        .catch(error=>{
                            toast.fire({
                                    icon: 'error',
                                    title: error.data.message
                            })
                        }); 
                    }
                    else {
                        this.companyList = [];
                        this.companyValue = null;
                    }                   
                },

                //reset field
                resetField () {
                    this.enterpriseValue = '';
                    this.companyValue = '';
                    this.$refs.file.value=null;
                    this.loadedStatus = 0;
                }

  },
  created(){
      this.getEnterprise();
  }
}    
</script>

<style scoped>
h1.loading {
    text-align: center;
    margin-top: -120px;
    margin-left: 200px;
}
</style>