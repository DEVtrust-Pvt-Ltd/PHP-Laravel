<template>

<table class="main-list-table table table-hover" :class="extraClass" >
    <thead class="thead-light">
    <tr class="hidden" id="Companylist" >
        <th v-for="h in header" :class="h.class">{{ h.title }}</th>
        <th>Action</th>
    </tr>
    <tr id="EmployeeDetail" v-show="!visible" class="hide">
        <th>Id</th>
        <th>Name</th>
        <th>Total Documents</th>
        <th>Create Date</th>
        <th>Status</th>
        <!-- <th data-v-2b7e51c4="" class="status">Action</th> -->
    </tr>
    
    <slot name="extra-header"></slot>
    </thead>
    <tfoot></tfoot>
    <tbody >
        
    <tr v-for="(item, idx) in list" class="clickable" id="Companylists" >

        <td v-for="(cell, idxx) in item.row" :class="cell.class">
            
            <span v-if="idxx===4 ">
                <span v-if="cell.value == 1" class="text">
                    Completed
                </span>
                <span v-if="cell.value == 2" class="text">
                    In-Progress 
                </span>
                <span v-if="cell.value == 3" class="text">
                    Errored 
                </span>
                <span v-if="cell.value == 4" class="text">
                    Terminated 
                </span>
            </span>
            <span v-else-if="idxx===3" >{{ cell.value }}</span>            
            <span v-else >{{ cell.value }}</span></td>

        <td class="controls-td">
            <!-- v-bind:href='onClickUrl' -->
            <a class="btn btn-primary btn-sm"   v-on:click="sendData(item.item.id)" > Details</a>
        </td>
    </tr>
    <tr v-for="valus in EmployeeDocLis" class="clickable hide" id="EmployeeDetails">
        <td>{{ valus.emp_id }}</td>
        <td>{{valus.name}}</td>
        <td>{{valus.total_documents}}</td>
        <td>{{valus.datetime}}</td> 
        <td> 
            <span v-if="valus.status == 1" class="text">
                Completed
            </span>
            <span v-if="valus.status == 2" class="text">
                In-Progress 
            </span>
            <span v-if="valus.status == 3" class="text">
                Errored 
            </span>
        </td>             
    </tr>   
   
    </tbody>
</table>

</template>


<script>
export default {
    name: "EntitiesDocumentsListTable",
    props: {
        extraClass: {
            type: String,
            default: "",
        },
        header: Array,
        list: Array,
    },
    data () {
        return {           
            onClickUrl: '' ,
            Detailslist:'',
            visible: true,
            EmployeeDocLis: [],             
        }  
          
    },
    methods: {
        sendData (ids) {            
            axios.get("/admin/isolved/detailslist",{ params: {id:ids}}).then(response => { 
                $("#Companylist, #Companylists").hide();
               //this.visible = true;
                $("#EmployeeDetails, #EmployeeDetail").show();
                $("#addItemTitle").html('').removeClass('btn btn-info btn-sm');
                $("#pageTitle").html("<a href='/admin/isolved/documents'>Employee Documents Details</a>");
                $(".addItemTitle").removeAttr("addItem");
               
                this.EmployeeDocLis = response.data;
                //console.log(response);
            });
            //this.onClickUrl  = '/admin/isolved/details/'+ids;
        },
        formatCompat: function (date) {
            return date;
        }       
    },
    // computed: {
    //     fullname: function () {
    //     return this.search
    //     }
    // }
      
    
}

</script>

<style scoped lang="scss">
table.main-list-table {
    width: 100%;

    thead th {
        font-weight: bold;
    }

    td.controls-td {
        width: 5%;
        white-space: nowrap;
    }

    td.boolean {
        /*text-align: center;*/
    }

    tbody tr.clickable {
        cursor: pointer;
    }
}


td .text{  
    width: 123px;
    height: 36px;
    left: 1067px;
    top: 292px;
    z-index: 35;
    color: rgb(37, 155, 36);
    background-color: rgb(173, 210, 130);
    border-color: rgb(37, 155, 36);
    border-radius: 3px;
    font-size: 16px;
    padding: 0px;
    border-width: 1px;
    border-style: solid;
    text-align: center;
    line-height: 23px;
    font-weight: bold;
    font-style: normal;
    opacity: 1;
}
</style>
