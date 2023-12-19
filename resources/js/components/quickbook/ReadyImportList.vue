<template>
<div class="container-fluid">
    <div class="form-steps">
        <span class="step-childs">
            <span class="steps">1</span>Add file
        </span>
        
        <span class="step-childs">
            <span class="steps">2</span>Validate file
        </span>

        <span class="step-childs font-bol">
            <span class="steps active-v">3</span>Review & Import
        </span>
    </div><br>
    <div class="row"></div>
    <div class="row">
            <table class="main-list-table table table-hover mt-2">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Invoice</th>
                        <th scope="col">Company</th>
                        <th scope="col">Location</th>
                        <th scope="col">InvoiceType</th>
                        <th scope="col">InvDate</th>
                        <th scope="col">DueDate</th>                                     
                        <th scope="col">Status</th>  
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="list.length === 0"><td>No record found</td></tr>   
                    <tr v-else v-for="(item, idx) in list">
                        <td>{{ item.invoice }}</td>
                        <td>{{ item.company }}</td>
                        <td>{{ item.location }}</td>
                        <td>{{ item.invoice_type }}</td>
                        <td>{{ item.inv_date }}</td>
                        <td>{{ item.due_date }}</td>
                        <td class="text-success">Ready</td>
        
                    </tr>
                </tbody>
            </table>
    </div>
    <div class="row">
            <div class="mb-3 button-class">  
                <button v-on:click="returnBack()"  class="btn btn-danger mr-2">Cancel</button>
                <button v-on:click="importData()"  class="btn btn-primary">Import</button>
            </div>   
            <div class="col-sm-3"> <h1 class="loading" v-if="1 === loadedStatus"><img src="/img/loading.gif" /></h1></div>
    </div>
</div>
</template>

<script>
export default { 

  data() {
    return {
        list:[],
        getAllList:[],
        loadedStatus: 1,
        filterValue:""
    }
  },
  methods: {
            getImportedList(){
                   axios.get('/admin/get_ready_imported_list').then(res => {
                    if(res.data.status) {
                        this.list = res.data.data;
                        this.getAllList = res.data.data;
                        toast.fire({
                                    icon: 'success',
                                    title: res.data.message
                                })  
                        this.loadedStatus = 0;           
                    }
                    else {
                        toast.fire({
                                    icon: 'error',
                                    title: res.data.message
                                })
                        this.loadedStatus = 0;          
                    }
                });
            },
            importData(){
                axios.get('/admin/import_list').then(res => {
                    if(res.data.status) {
                        toast.fire({
                                    icon: 'success',
                                    title: res.data.message
                                })
                        window.location.href = '/admin/show_imported_list';        
                    }
                    else {
                            toast.fire({
                                    icon: 'error',
                                    title: res.data.message
                                    })
                    }
                });
            },
            returnBack(){
                axios.get('/admin/return_back').then(res => {
                    window.location.href  = res.data.url;
                });
            }
  },            
  created(){
      this.getImportedList();
  }
}    
</script>
<style scoped>
h1.loading {
    text-align: center;
    margin-left: 220px;
    margin-top: -12px;
}
.button-class{
    width: 100%;
    display: flex;
    justify-content: flex-end;
    padding-right: 15px;
}
</style>