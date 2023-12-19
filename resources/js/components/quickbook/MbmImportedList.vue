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
            <span class="steps active-v">3</span>Import Result
        </span>
    </div><br>
    <div class="row">
       <label for="filter" class="filter-box">Filter:</label>
        <select id="filter" v-model="filterValue" @change="filterData(filterValue)">
            <option value="">Select</option>
            <option value="0">Imported</option>
            <option value="1">Not Imported</option>
        </select>
    </div>
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
                        <td v-if="item.error_status === 1" class="text-danger">Not import</td>
                        <td v-else class="text-success">Imported</td>
                    </tr>
                </tbody>
            </table>
    </div>
    <div class="row">
             <div class="col-sm-3" style="float: right;margin-left: 900px;">  
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
        getAllList:[],
        loadedStatus: 1,
        filterValue:""
    }
  },
  methods: {
            getImportedList(){
                   axios.get('/admin/get_mbm_imported_list').then(res => {
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
            filterData(value){
                if(value){
                    const filtered =  this.getAllList.filter(function(data) {
                        return data.error_status == value;
                    });
                    this.list = filtered;
                }
                else {
                    this.list = this.getAllList;
                }    
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
</style>