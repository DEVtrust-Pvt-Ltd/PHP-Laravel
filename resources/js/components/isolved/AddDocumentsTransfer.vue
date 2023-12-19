<template>
<div class="list-wrapper" :class="extraClass">
    <div class="card">
        <div class="card-header">
            <a href="/admin/isolved/documents-transfer-list">Company list</a> &gt; {{pageTitle}}
        </div>

        <div class="card-body">
            <div class="form-group row">
                <div class="col-sm-12">

                <div class="col-sm-12">
                    <div class="row">
                       <div class="col-sm-2" >
                            <b>SOURCE</b>
                        </div>
                    </div>
                        <div clear="all"/>
                        <div clear="all"/>
                    <div class="row">
                        <div class="col-sm-2">
                            <input type="radio" value="1" id="source_sftp" name="source" v-on:click="SourceSftp()"> SFTP
                        </div>
                        <div class="col-sm-2">
                            <input type="radio" value="2" id="source_isolve" name="source" v-on:click="sourceIsolve()"> iSolved
                        </div>
                        
                    </div> <br>
                    <div clear="all"/>
                    <div class="row source_dropdown">                       
                        <div class="col-sm-1">
                            &nbsp;
                        </div>
                        <div class="col-sm-2">
                            Enterprises
                        </div>
                        <div class="col-sm-9">                
                            <select v-on:input="getComapny($event)" id="enterpriseid" ref="select_el" style="width: 80%; padding: 10px;">
                                <option selected ="selected" v-bind:value='-1' >Select Enterprise</option>
                                <option v-for="values in enterprise" v-bind:value='values.iSolvedClient' v-text="values.name"  >{{values.name}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row source_textbox" style="display:none;" >                       
                        <div class="col-sm-1">
                            &nbsp;
                        </div>
                        <div class="col-sm-2">
                           FOLDER PATH
                        </div>
                        <div class="col-sm-9">                
                             <select id="sftp" style="width: 80%; padding: 10px;" v-on:input="ChangProperty($event)" >
                                <option selected ="selected" value="">Select path</option>
                                <option value='incoming' >Incoming</option>
                                <option value='outgoing'>Outgoing</option>
                            </select>
                            <br>Files must be uploaded under the {folder path}/{Enterprise code}/{Company code} 
                        </div>
                    </div>
                    <br>
                    <div class="row source_dropdown ">                       
                        <div class="col-sm-1">
                            &nbsp;
                        </div>
                        <div class="col-sm-2">
                            Company
                        </div>
                        <div class="col-sm-9">                 
                            <select ref="select_el" id="companyid" v-on:input="CheckMetaData($event,'source')" style="width: 80%; padding: 10px;
                            ">
                                <option selected ="selected" v-bind:value='-1' > Select Company </option>
                                <option v-for="values in company" v-bind:value='values.legalCode' v-text="values.legalName"  >{{values.legalName}}</option>
                            </select>
                            <div class="source" id="source"> </div>
                        </div>
                    </div>
                    <div class="row source_textbox" style="display:none;" >                       
                        <div class="col-sm-1">
                            &nbsp;
                        </div>
                        <div class="col-sm-2">
                           FILE NAMING CONVENTION
                        </div>
                        <div class="col-sm-9">                
                            <input type="text" id="in_ou" placeholder="Select folder path"  name="outgoing"  style="width: 80%; padding: 10px; " disabled >
                            <br>Use the following label to define the file naming convention, the allowed delimeters are dash (-) and underscore (_) <br>
                            Label Available:   {Last4SSN}    {FirstName}    {LastName}    {EmployeeId}    {SSN}  {DocumentName}  
                        </div>
                    </div> 
                </div>
                <br>
                <div class="col-sm-12">
                    <div class="row">
                       <div class="col-sm-2" >
                            <b>DESTINATION</b>
                        </div>
                        </div>
                        <div clear="all"/>
                        <div clear="all"/>
                        <div class="row">
                        <div class="col-sm-2">
                            <input type="radio" value="1" id="destination_sftp" name="destination" v-on:click="destinationSftp()"> SFTP
                        </div>
                        <div class="col-sm-2">
                            <input type="radio" value="2" id="destination_isolve" name="destination" v-on:click="destinationIsolve()"> iSolved
                        </div>
                        
                    </div> <br>
                    <div clear="all"/>
                    <div class="row destination_dropdown">
                        <div class="col-sm-1">
                            &nbsp;
                        </div>
                        <div class="col-sm-2">
                            Enterprises
                        </div>
                        <div class="col-sm-9">           
                            <select v-on:input="getComapnyDestination($event)" id="EnterPrisesDestId" ref="select_el" style="width: 80%; padding: 10px;
                            ">
                                <option selected ="selected" v-bind:value='-1' >Select Enterprise</option>
                                <option v-for="values in enterprise" v-bind:value='values.iSolvedClient' v-text="values.name"  >{{values.name}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row destination_textbox" style="display:none;" >                       
                        <div class="col-sm-1">
                            &nbsp;
                        </div>
                        <div class="col-sm-2">
                           FOLDER PATH
                        </div>
                        <div class="col-sm-9">                
                            <input type="text" placeholder="/incoming/" name="incoming" id="incoming"  style="width: 80%; padding: 10px; " disabled >
                            <br>Files will be downloaded at  {folder path}/{Enterprise code}/{Company code}/ 
                        </div>
                    </div>
                    <br>
                    <div class="row destination_dropdown">
                        <div class="col-sm-1">
                            &nbsp;
                        </div>
                        <div class="col-sm-2">
                            Company
                        </div>
                        <div class="col-sm-9">               
                            <select ref="select_el" id="CompanyDestinationid" v-on:input="CheckMetaData($event,'destination')"  style="width: 80%; padding: 10px;
                            ">
                                <option selected="selected" v-bind:value='-1'> Select Company </option>
                                <option v-for="values in companyDestination" v-bind:value='values.legalCode' v-text="values.legalName" >{{values.legalName}}</option>
                            </select>
                            <div class="Destination" id="Destination"> </div>
                        </div>                       
                    </div> 
                    <div class="row destination_textbox" style="display:none;" >                       
                        <div class="col-sm-1">
                            &nbsp;
                        </div>
                        <div class="col-sm-2">
                           FILE NAMING CONVENTION
                        </div>
                        <div class="col-sm-9">                
                            <input type="text" placeholder="{CompanyCode}_{EmployeeName}_{EmployeeId}_{AllowAccessPermission}_{AccessTypePermission}_{DocumentName}" name="incoming"  style="width: 80%; padding: 10px; " disabled> 
                        </div>
                    </div>
                </div> 

                <div class="d-flex justify-content-end mt-3 pr-4">
                    <button v-on:click="downloadDocument()" class="btn btn-primary btn-lg transfer " >
                        {{ isSaving ? 'Transfering...' : 'Transfer'}}
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
    name: "AddDocumentsTransfer",
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
                    titleAddOn: "Select Company",
                },
            },

            currentTab: "general",          

            document: {
                id: 0,
                concepts: [],
            },
            enterprise: [],
            company: [],
            companyDestination: [],
            companyid: null,
            enterpriseid: null,

            allowLoading: true,
            loadedStatus: 1,

        }
    },
    methods: { 
        
        SourceSftp() {            
            var source_sftp = $("#source_sftp").val();
            if(source_sftp == 1) {
               $(".source_dropdown").css("display","none");
               $(".source_textbox").css("display","");
               $(".destination_dropdown").css("display","");
                $(".destination_textbox").css("display","none");
               $("#destination_isolve").prop('checked', true);
               $("#destination_sftp").prop('checked', false);
               $("#destination_sftp").attr("disabled", true);
               
                

               // $("#Destination").html('');
                //$("#CompanyDestinationid").css("border-color","black");

                $("#companyid option").prop("selected", false);
                $("#enterpriseid option").prop("selected", false);

                $("#source").html('');
                $("#companyid").css("border-color","black");

              
            }
        },

        sourceIsolve() {
            var source_sftp = $("#source_isolve").val();
            if(source_sftp == 2) {
                $("#sftp option").prop("selected", false);
               $("#destination_sftp").attr("disabled", false);
                $(".source_dropdown").css("display","");
               $(".source_textbox").css("display","none");               
            }
        },

        destinationSftp() {
            var destination_sftp = $("#destination_sftp").val();            
            if(destination_sftp == 1) {
                $(".destination_dropdown").css("display","none");
                $(".destination_textbox").css("display","");                        
                $("#Destination").html('');
                $("#CompanyDestinationid").css("border-color","black");
                $("#CompanyDestinationid option").prop("selected", false);
                $("#EnterPrisesDestId option").prop("selected", false);
            }
        },

        destinationIsolve() {
            $(".destination_dropdown").css("display","");
            $(".destination_textbox").css("display","none");
        },

        getEnterprise() {            
            axios.get("/admin/isolved/get_enterprise").then(response => {                
                this.enterprise = response.data; 
                //console.log(response.data);
            });
        },
        ChangProperty(event) {
            if(event.target.value == "incoming") {
                $("#in_ou").attr("placeholder", "{CompanyCode}_{EmployeeName}_{EmployeeId}_{AllowAccessPermission}_{AccessTypePermission}_{DocumentName}");

            }else if(event.target.value == "outgoing") {
                $("#in_ou").attr("placeholder", "{CompanyCode}_{EmployeeName}_{EmployeeSSN} / {DocumentName}");
            }else{
                $("#in_ou").attr("placeholder", "Select folder path");
            }
        },

        CheckMetaData(evt,condition) { // check credentials in meta data
            
            const compantCode = event.target.value;

                    const EnterPrisesDestId = $("#EnterPrisesDestId").val();
                    const enterpriseid = $("#enterpriseid").val();
                    const  destination_isolve = $('input[name="destination"]:checked').val();
                    const  source_isolve = $('input[name="source"]:checked').val();
                if(condition == 'destination') {                   
                  
                    if(source_isolve == 1 && destination_isolve == 2 && EnterPrisesDestId !=-1 && compantCode !=-1) {
                        $("#outgoing").attr("placeholder", "/outgoing/"+EnterPrisesDestId+"/"+compantCode+"/");
                    }else{
                        $("#outgoing").attr("placeholder", "/outgoing/"+EnterPrisesDestId+"/");
                    }
                } else if(condition == 'source') {
                    if(source_isolve == 2 && destination_isolve == 1 && enterpriseid !=-1 && compantCode !=-1) {
                        $("#incoming").attr("placeholder", "/incoming/"+enterpriseid+"/"+compantCode+"/");
                    } else {
                        $("#incoming").attr("placeholder", "/incoming/"+enterpriseid+"/");
                    }
                }

            if((condition == 'destination' || condition == 'source') && compantCode !=-1) {
                
                axios.get("/admin/isolved/checkmetadata", { params: {types:'Metadata',   compantCode:compantCode,condition:condition}}).then(response => {
                    if(parseInt(response.data.condition) == 1) {
                        if(condition == 'destination') {                                
                            $("#CompanyDestinationid").css("border-color","green");
                            $("#Destination").html('<span style="color:green;"><i class="fa fa-check" aria-hidden="true"></i> Right Credentials</span>');
                        } else if(condition == 'source') {
                            $("#companyid").css("border-color","green");
                            $("#source").html('<span style="color:green;"><i class="fa fa-check" aria-hidden="true"></i> Right Credentials</span>');
                        }
                        
                    } else if(parseInt(response.data.condition) == -1) {
                        if(condition == 'destination') {
                            $("#CompanyDestinationid").css("border-color","red");
                            $("#Destination").html('<span style="color:red;"><i class="fa fa-times" aria-hidden="true"></i> please enter the secret id & client id in Meta data</span>');
                        } else if(condition == 'source') {
                            $("#companyid").css("border-color","red");
                            $("#source").html('<span style="color:red;"><i class="fa fa-times" aria-hidden="true"></i> please enter the secret id & client id in Meta data</span>');
                        }
                    } else if(parseInt(response.data.condition) == -101) {
                            if(condition == 'destination') {
                            $("#Destination").html('');
                            $("#CompanyDestinationid").css("border-color","black");
                        } else if(condition == 'source') {
                            $("#source").html('');
                            $("#companyid").css("border-color","black");
                        }
                        alert("Please select the company");
                        
                    } else {
                        if(condition == 'destination') {
                            $("#Destination").html('');
                            $("#CompanyDestinationid").css("border-color","black");
                        } else if(condition == 'source') {
                            $("#source").html('');
                            $("#companyid").css("border-color","black");
                        }
                        alert("Teachnical issue, support developer");
                    }           
                });
            } else {
                if(condition == 'destination') {
                    $("#Destination").html('');
                    $("#CompanyDestinationid").css("border-color","black");
                } else if(condition == 'source') {
                    $("#source").html('');
                    $("#companyid").css("border-color","black");
                }
            }  
        },

        getComapny(event) {
            const  source_isolve = $('input[name="source"]:checked').val();
            
            if(source_isolve === undefined){
                $("#enterpriseid option").prop("selected", false);
                alert("Please choose the source");
                return false;
            }

            const destination_isolve = $('input[name="destination"]:checked').val();
                if(source_isolve == 2 && destination_isolve == 1 && event.target.value != -1) {
                    $("#incoming").attr("placeholder", "/incoming/"+event.target.value+"/");
                } else {
                     $("#incoming").attr("placeholder", "/incoming/");
                }


            if(!this.allowLoading) return;
            this.loadedStatus = 0;

            if(event.target.value) {
          
                axios.get("/admin/isolved/company",{ params: {types:'companys',enterprise_id:event.target.value}}).then(response => {
                    this.loadedStatus = 1;
                    this.company = response.data; 
                    //console.log(response.data);
                });
            } else {
                alert("Please setect the Enterprise");
            }
        },

        getComapnyDestination(event) {
           
            const  destination_isolve = $('input[name="destination"]:checked').val();
            if(destination_isolve === undefined){
                $("#EnterPrisesDestId option").prop("selected", false);
                alert("Please choose the Destination");
                return false;
            }

             const  source_isolve = $('input[name="source"]:checked').val();
                if(source_isolve == 1 && destination_isolve == 2) {
                    $("#outgoing").attr("placeholder", "/outgoing/"+event.target.value+"/");
                }

            if(!this.allowLoading) return;
            this.loadedStatus = 0;

            if(event.target.value){
                axios.get("/admin/isolved/company",{ params: {types:'companys',enterprise_id:event.target.value}}).then(response => {
                    this.loadedStatus = 1;
                    this.companyDestination = response.data; 
                    //console.log(response.data);
                });
            } else {
                alert("Please setect the Enterprise");
            }

        },
        

        downloadDocument() {
           const  Comp_id = document.getElementById('companyid').value;
           const  EnterpricesId = document.getElementById('enterpriseid').value;


           const  CompDestination_id = document.getElementById('CompanyDestinationid').value;
           const  EnterprisesDestinationId = document.getElementById('EnterPrisesDestId').value;
           const  source_isolve = $('input[name="source"]:checked').val();
           const  destination_isolve = $('input[name="destination"]:checked').val();
         // alert(Comp_id +'#'+ EnterpricesId +'#'+ CompDestination_id +'#'+ EnterprisesDestinationId);
            if(source_isolve === undefined && destination_isolve === undefined){
                alert("Please choose the checkbox of SFTP or iSolved button");
                return false;
            }

            const in_out_sftp = document.getElementById('sftp').value;
            if(in_out_sftp == '' && source_isolve == 1 && destination_isolve == 2){
                alert("Please selecte the folder path");
                return false;
            }

            if ((EnterpricesId != -1 || EnterprisesDestinationId !=-1) && (Comp_id != -1 || CompDestination_id != -1)) { 
                if(source_isolve == 2 && destination_isolve == 1) {
                    if (!confirm("You have successfully schedule the document downloading.")) {
                        return false;
                    }
                } else {
                    if (!confirm("You have successfully schedule the document uploading.")) {
                       return false;
                    }
                }              
            
            if(!this.allowLoading) return;
            this.loadedStatus = 0;
                axios.get("/admin/isolved/GetEmployee", { params: {types:'employees',compid:Comp_id, enterprisescode:EnterpricesId, CompDestination_id:CompDestination_id,EnterprisesDestinationId:EnterprisesDestinationId, actionstype:source_isolve+destination_isolve, sftpAction:in_out_sftp}}).then(response => {
                    // this.enterprise = response.data.entity; 
                        this.loadedStatus = 1;  
                        if(source_isolve == 2 && destination_isolve == 1) {  
                             alert("your documents download successfully.");
                        } else {
                            alert("Your documents upload successfully.");
                        }                 
                      
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
