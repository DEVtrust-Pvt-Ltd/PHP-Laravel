<template>
<div class="container-fluid">
    <div class="form-steps">
        <span class="step-childs">
            <span class="steps">1</span>Add file
        </span>
        <span class="step-childs font-bol">
            <span class="steps active-v">2</span>Validate file
        </span>
        <span class="step-childs">
            <span class="steps">3</span>Review & Import
        </span>
    </div>
    <div class="row">
            <table class="main-list-table table table-hover mt-2">
                <tr><td colspan="8"><input type="number" class="form-control" placeholder="Location" v-model="filterWord" @keypress="isNumber($event)"></td> <td>&nbsp;</td> <td><button class="btn btn-sm btn-secondary" @click="filterValue()">Search</button></td></tr>
                <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Invoice</th>
                        <th scope="col">Company</th>
                        <th scope="col">Location</th>
                        <th scope="col">Category</th>
                        <th scope="col">InvDate</th>
                        <th scope="col">DueDate</th>                     
                        <th scope="col">Corrections</th>                     
                        <th scope="col" colspan="2">Action</th>  
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="list.length === 0"><td>No record found</td></tr>
                    <tr v-else v-for="(item, idx) in list">
                        <td v-if="item.location_status !=1 || item.glmapping_status != 1" class="text-danger">
                            <span v-if="item.location_status !=1"><i class="fa fa-exclamation-circle"></i> Location not found</span>
                            <span v-else-if="item.glmapping_status !=1"><i class="fa fa-exclamation-circle"></i> Mapping not found</span>
                        </td>
                        <td v-if="item.location_status == 1 && item.glmapping_status == 1" class="text-success"><i class="fa fa-check-circle " ></i> Ready for import</td>
                        <td>{{ item.invoice }}</td>
                        <td>{{ item.company }}</td>
                        <td>{{ item.location }}</td>
                        <td>{{ item.glmapping_input }}</td>
                        <td>{{ item.inv_date }}</td>
                        <td>{{ item.due_date }}</td>
                        <td v-if="item.location_status == 1 && item.glmapping_status !=1">
                            <button  class="btn btn-warning btn-sm" data-toggle="modal"  :data-target="'#exampleModalCenter' + idx" >Update</button>
                            <!-- Modal -->
                            <div class="modal fade" :id=" 'exampleModalCenter'+ idx" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header bg-muted">
                                        <h5 class="modal-title" id="exampleModalCenterTitle">UPDATE RECORD</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form>
                                            <div class="form-group row">
                                                <label for="invoice" class="col-sm-4 col-form-label">INVOICE</label>
                                                <div class="col-sm-6">
                                                <input class="form-control" id="invoice" v-bind:value="item.invoice" disabled>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="input" class="col-sm-4 col-form-label">INPUT</label>
                                                <div class="col-sm-6">
                                                <input class="form-control" id="input" v-bind:value="item.glmapping_input" disabled>
                                                </div>
                                            </div>
                                             <div class="form-group row">
                                                <label for="output" class="col-sm-4 col-form-label">OUTPUT</label>
                                                <div class="col-sm-6">
                                                <input type="text" class="form-control" v-model="update.output">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="transaction" class="col-sm-4 col-form-label">DEBIT/CREDIT</label>
                                                <div class="col-sm-6">
                                                <select v-model="update.transtype" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="debit">Debit</option>
                                                        <option value="credit">Credit</option>
                                                </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="description" class="col-sm-4 col-form-label">DESCRIPTION</label>
                                                <div class="col-sm-6">
                                                <input type="text" class="form-control" id="description"  v-model="update.description"> 
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" @click="fieldUpdate(item,idx)" >Update</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Model -->
                        </td>
                         <td v-else >
                        </td>
                        <td>
                            <button class="btn btn-danger btn-sm" @click="deleteImportRow(item,idx)">Ignore</button>
                        </td>
                    </tr>
                </tbody>
            </table>
    </div>
    <div class="row">
             <div class="col-md-12">  
                <button v-on:click="showReadyList()"  class="btn btn-primary next-btn">NEXT</button>
             </div><br><br>   
             <div class="col-sm-3"> <h1 class="loading" v-if="1 === loadedStatus"><img src="/img/loading.gif" /></h1></div>
    </div>
</div>
</template>

<script>
export default { 

  data() {
    return {
        list:[],
        filterList:[],
        loadedStatus: 1,
        filterWord:null,
        update:{
            output:'',
            description:'',
            transtype:''
        }
    }
  },
  methods: {
           getValidateGlImportList() {
                axios.get('/admin/get_validate_gl_import_list').then(res => {
                    if(res.data.status){
                        this.list = res.data.data;
                        this.filterList = res.data.data;
                        const data = toast.fire({
                                                    icon: 'success',
                                                    title: res.data.message
                                                })
                        this.loadedStatus = 0;
                    }
                    else {
                        const data = toast.fire({
                                                    icon: 'error',
                                                    title: res.data.message
                                                })
                        this.loadedStatus = 0;
                    }    
                });
           },
            fieldUpdate(value,index) {
                if(!value.glmapping_status) {
                    const outputValue    = this.update.output;
                    const transTypeValue = this.update.transtype;
                    const description    = this.update.description;
                    if(outputValue) {
                        if(transTypeValue){
                            if(description) {
                                let formData = new FormData();
                                formData.append('id', value.id);
                                formData.append('output', outputValue);
                                formData.append('transType', transTypeValue);
                                formData.append('glmappingInput', value.glmapping_input);
                                formData.append('description', description);
                                axios.post('/admin/update_glmapping_field',formData).then(res => {
                                        if(res.data.status) {
                                            toast.fire({
                                                        icon: 'success',
                                                        title: res.data.message
                                                    })
                                            location.reload();           
                                        }
                                        else {
                                                toast.fire({
                                                        icon: 'error',
                                                        title: res.data.message
                                                        })
                                        }
                                });
                            }
                            else{
                                        toast.fire({
                                            icon: 'error',
                                            title: 'Please input description'
                                            })
                            }
                        }
                        else {
                                toast.fire({
                                            icon: 'error',
                                            title: 'Please input transaction type'
                                            })
                        }
                    }
                    else {
                            toast.fire({
                                        icon: 'error',
                                        title: 'Please input output value'
                                        })
                    }    
               } 
            },   
            deleteImportRow(item,index) {
                if(confirm("Do you really want to delete?")){
                    axios.delete('/admin/delete_import_row/'+item.id).then(res => {
                            if(res.data.status) {
                                this.list.splice(index, 1)  
                                toast.fire({
                                            icon: 'success',
                                            title: res.data.message
                                        })         
                            }
                            else {
                                    toast.fire({
                                            icon: 'error',
                                            title: res.data.message
                                            })
                            }
                    });
                }    
            },
            showReadyList(){
               window.location.href = '/admin/show_ready_import_list';
            },
            filterValue(){
                if(this.filterWord){
                    let formData = new FormData();
                    formData.append('word', this.filterWord);
                    axios.post('/admin/filtered_validate_gl_import_list',formData).then(res => {
                       this.list = res.data.data;
                    });
                }else{
                    this.list = this.filterList;
                }
            },
            isNumber(evt){
                        evt = (evt) ? evt : window.event;
                        var charCode = (evt.which) ? evt.which : evt.keyCode;
                        if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                            toast.fire({
                                            icon: 'error',
                                            title: 'Only numbers allow !'
                                            })
                        } else {
                            return true;
                        }
            }
    },            
  created(){
      this.getValidateGlImportList();
  }
}    
</script>
<style scoped>
h1.loading {
    text-align: center;
    margin-left: 420px;
    margin-top: -12px;
}
.next-btn{
    display: block;
    margin-left: auto;
    margin-right: 74px;
    margin-bottom: 30px;

}
</style>