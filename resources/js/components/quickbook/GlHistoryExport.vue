import * as download from 'downloadjs';

<template>
<div class="container-fluid">
    <form @submit.prevent="submit">
        <div class="row">
            <div class='col-sm-3'>
                <div class="form-group">
                <label for="from_date" class="font-weight-bold">From:</label>
                    <date-picker v-model="fields.from_date" valueType="format" ></date-picker>
                </div>
            </div>     
            <div class='col-sm-3'>
                <div class="form-group">
                <label for="to_date" class="font-weight-bold">To:</label>
                    <date-picker v-model="fields.to_date" valueType="format"></date-picker>
                </div>
            </div>
                    
            <div class='col-sm-3'>
                <div class="form-group">
                <label for="from_date" class="font-weight-bold">Select Company:</label>
                       <MultiSelect v-bind:companyList="companyList" />
                </div>
            </div>      
        </div>
        <div class="row">
            <div class='col-sm-3'>
                <div class="form-group">
                <label for="from_date" class="font-weight-bold">Select Class(optional):</label>
                   <multiselect v-model="value" :options="locationList" placeholder="Select" label="class" track-by="class"  :multiple="true" @input="getLocationId"></multiselect>
                </div>
            </div>
            </div>   
        <div class="row">
            <div class='col-sm-3'>
                <div class="buttons-wrapper">
                    <button type="submit" class="btn btn-primary">Export</button>
                </div>
            </div> 
            <div class="col-sm-3"> <h1 class="loading" v-if="1 === loadedStatus"><img src="/img/loading.gif" /></h1></div>
        </div>
        <div class="row">
           <br>
           <div v-if="errors && errors.name" class="text-danger">{{ errors.message }}</div>
        </div>    
    </form> 
</div>
</template>

<script>
import MultiSelectCompany from "./Multiselect"
import Multiselect from 'vue-multiselect';
import DatePicker from 'vue2-datepicker';
let url;
export default {  
  components: { DatePicker,Multiselect }, 
  data() {
    return {
      value: [],
      fields: {},
      errors: {},
      locationList: [],
      companyList: [],
      locationId:[],
      companyId:null,
      loadedStatus: 0,
    }
  },
  methods: {

        submit() {
            this.loadedStatus = 1;
            if(this.fields.from_date && this.fields.to_date) {
               if(this.companyId) {
                  
                    url = this.fields.from_date+'/'+this.fields.to_date+'/'+ this.companyId+'/'+this.locationId;
                    this.$Progress.start();
                    axios.get('/admin/gl_export/'+url, this.fields).then(res => {
                        if(res.data.status){
                            window.open(res.data.data)
                            const data = toast.fire({
                                    icon: 'success',
                                    title: res.data.message
                            })
                            if(data){
                                this.$Progress.finish();
                                this.loadedStatus = 0;
                            }
                        } 
                        else {
                           
                            this.$Progress.finish()
                            toast.fire({
                                    icon: 'error',
                                    title: res.data.message
                            })
                            this.loadedStatus = 0;
                        }   
                    })
               }
               else {
                    this.loadedStatus = 0;
                    toast.fire({
                                icon: 'error',
                                title: 'please select company'
                        })
               }            
            }
               
            else {
                this.loadedStatus = 0;
                 toast.fire({
                            icon: 'error',
                            title: 'please select date range'
                    })
            }
        },

        // Get Company List
        getCompany() {
            axios.get('/admin/get_company_list').then(res => {
                this.companyList = res.data.data
            })
            .catch(error=>{
                 toast.fire({
                        icon: 'error',
                        title: error.data.message
                })
            });    
        },

        //Selected Locations Id
        getLocationId() {
            if(this.value){
                this.locationId = [];
                this.value.forEach(element => {
                      this.locationId.push(element.location_id);
                });
            }             
        }
  },
  created(){
      this.getCompany();
  }

  
}
</script>
<style scoped>
h1.loading {
    text-align: center;
    margin-top: -90px;
    margin-left: -2px;
    
}
</style>