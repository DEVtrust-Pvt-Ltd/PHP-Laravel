<template>
<div class="wrapper">
    <div class="row">
        <div class="col text-left">
            <label for="show_imported_chb">Show Imported : <input type="checkbox" id="show_imported_chb" v-model="showImported" /></label>
        </div>
        <div class="col text-right">
            Enterprise : <enterprise-selector concept="DEN" v-model="enterpriseId"></enterprise-selector>
        </div>
    </div>

    <div class="row new-files">
        <div class="col text-right">
            <div v-if="newFilesStatus === 'loaded'">
                <div v-if="0 < newFiles.length">
                    <button class="btn btn-sm btn-info" @click="checkNewFiles">New files available!</button>
                </div>
                <div v-else>
                    No new files
                </div>
            </div>
            <div v-else-if="newFilesStatus === 'loading'">
                Loading ...
            </div>
        </div>
    </div>

    <div class="files-list">
        <denny-single-file v-for="f in filesListFiltered" :file="f" :key="f.filePath"></denny-single-file>
    </div>
    <h1 class="loading" v-if="isLoading"><img src="/img/loading.gif" /></h1>

    <div id="missingMappingModal" ref="missing_mapping_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Missing Mapping</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" v-if="addMissingList.length">
                    <table class="table table-striped table-bordered missing-mapping-table">
                        <thead>
                        <tr>
                            <th class="type">Type</th>
                            <th class="record-type">Record Type</th>
                            <th class="active">Active</th>
                            <th class="input">Input</th>
                            <th class="output">Output</th>
                            <th class="db-cr">DB/CR</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(m, idx) in addMissingList">
                            <td class="type"><input type="text" class="form-control form-control-sm" v-model="m.type" /></td>
                            <td class="record-type"><select class="form-control form-control-sm" v-model="m.record_type"><option v-for="rt in recordTypes" :value="rt" v-text="rt"></option></select></td>
                            <td class="active text-center"><input type="checkbox" class="form-control form-control-sm" v-model="m.active" /></td>
                            <td class="input"><input type="text" class="form-control form-control-sm" v-model="m.input" /></td>
                            <td class="output"><input type="text" class="form-control form-control-sm" v-model="m.output" :disabled="!m.active" /></td>
                            <td class="db-cr"><select class="form-control form-control-sm" v-model="m.debit_credit" :disabled="!m.active"><option value="debit">DB</option><option value="credit">CR</option></select></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="saveMissingMapping()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div id="quickBalanceCheckModal" ref="quick_balance_check_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Quick Balance Check [{{currentFile.fileDate}}]</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered quick-balance-check-table">
                        <thead>
                        <tr>
                            <th >Date</th>
                            <th >Location</th>
                            <th >Credit</th>
                            <th >Debit</th>
                            <th >Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(b) in currentFile.balances" :class="b.balance !== '0.00' ? 'quick-list-unbalanced' : ''">
                            <td >{{b.date}}</td>
                            <td >{{b.store}}</td>
                            <td class="text-right">{{b.credit}}</td>
                            <td class="text-right">{{b.debit}}</td>
                            <td class="text-right">{{b.balance}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="newFilesModal" ref="new_files_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">New Files</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered quick-balance-check-table">
                        <thead>
                        <tr>
                            <th >Folder</th>
                            <th >Filename</th>
                            <th >Download</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(f, idx) in newFiles">
                            <td >{{f.enterpriseIdentifier}}</td>
                            <td >{{f.fileName}}</td>
                            <td >
                                <input type="checkbox" class="form-control" v-model="f.download" />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="downloadSelectedFiles">Download Selected</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div style="display: none;">
        <form method="post" target="_blank" action="/admin/denny/check_file_balance" ref="check_balance_form">
            <input type="hidden" name="_token" :value="csrf" />
            <input type="hidden" name="enterpriseId" :value="enterpriseId" />
            <input type="hidden" name="conceptId" :value="conceptId" />
            <input type="hidden" name="filePath" :value="currentFile.filePath" />
        </form>

        <form method="post" target="_blank" action="/admin/denny/raw_data" ref="raw_data_form">
            <input type="hidden" name="_token" :value="csrf" />
            <input type="hidden" name="enterpriseId" :value="enterpriseId" />
            <input type="hidden" name="conceptId" :value="conceptId" />
            <input type="hidden" name="filePath" :value="currentFile.filePath" />
        </form>
    </div>
</div>
</template>

<script>
import EnterpriseSelector from "../tools/EnterpriseSelector";
import DennySingleFile from "./DennySingleFile";
export default {
    name: "ParseDennyFinanceMain",
    components: {DennySingleFile, EnterpriseSelector},
    data() {
        return {
            enterpriseId: null,
            filesList: [],
            isLoading: false,
            addMissingList: [],
            recordTypes: [
                "", "FCLO", "FCOM", "FFIN", "FSAL", "FTEN", "FADJ", "FDEP", "FSFT", "FMSL", "FCPR",
            ],

            conceptId : 1, // Hardcode !!!!

            currentFile: {
                filePath: "",
            },
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

            newFilesStatus: null,
            newFiles: null,

            showImported: false,
        }
    },

    methods: {
        async loadFilesList() {
            this.isLoading = true;
            this.filesList = (await axios.get('/admin/denny/files/finance/' + this.enterpriseId)).data;
            this.isLoading = false;

            this.newFilesStatus = 'loading';

            const r = (await axios.get('/admin/denny/new_files/finance/' + this.enterpriseId)).data;
console.log("X", r);

            this.newFiles = r.map(x => {
                x.download = true;
                return x;
            });
            this.newFilesStatus = 'loaded';
        },

        async importFile(file) {
            file.busy = true;
            const response = (await axios.post('/admin/denny/import_file', {
                enterpriseId: this.enterpriseId,
                conceptId: this.conceptId,
                filePath: file.filePath,
            })).data;

            file.busy = false;
            if(response.ok) {
                alert("File was imported ok.");

                this.filesList.find(x => {
                    return file.fileName === x.fileName;
                }).status = 'imported';

                // this.filesList.splice(this.filesList.findIndex(x => {
                //     return file.fileName === x.fileName;
                // }), 1);
            } else {
                alert("PROBLEM : " + response.error)
            }

// console.log(response);
        },

        addMissingMapping(file) {
            // console.log("add missing mapping", file);
            this.addMissingList = file.missingMapping.map(item => {
                return {
                    id: "new",
                    input: item.input,
                    output: "",
                    type: "DSR",
                    record_type: item.record_type,
                    debit_credit: "debit",
                    active: true,
                }
            });
            $(this.$refs.missing_mapping_modal).modal();
        },

        async saveMissingMapping() {
            this.filesList = [];
            const response = (await axios.post("/admin/hierarchy/enterprises/" + this.enterpriseId + "/gl_mapping_append", {
                conceptId: this.conceptId,
                list: this.addMissingList,
            })).data;
            $(this.$refs.missing_mapping_modal).modal('hide');
            this.loadFilesList();
        },

        checkBalance(file) {
            this.currentFile = file;
            this.$nextTick(() => {
                this.$refs.check_balance_form.submit();
            });
        },

        quickBalanceList(file) {
            this.currentFile = file;
            $(this.$refs.quick_balance_check_modal).modal();
        },

        checkNewFiles() {
            $(this.$refs.new_files_modal).modal();
        },

        async downloadSelectedFiles() {
            this.filesList = [];
            this.newFilesStatus = null;
            $(this.$refs.new_files_modal).modal('hide');
            this.isLoading = true;
            await axios.post("/admin/denny/download", {
                new_files: this.newFiles.filter(x => x.download),
            });

            this.loadFilesList();
        },

        rawData(file) {
            this.currentFile = file;
            this.$nextTick(() => {
                this.$refs.raw_data_form.submit();
            });
        },

        async rollbackImport(file) {
            file.busy = true;
            const response = (await axios.post('/admin/denny/rollback_import_file', {
                enterpriseId: this.enterpriseId,
                conceptId: this.conceptId,
                filePath: file.filePath,
            })).data;

            file.busy = false;
            if(response.ok) {
                alert("Import was rolled back ok.");

                this.filesList.find(x => {
                    return file.fileName === x.fileName;
                }).status = 'new';

                // this.filesList.splice(this.filesList.findIndex(x => {
                //     return file.fileName === x.fileName;
                // }), 1);
            } else {
                alert("PROBLEM : " + response.error)
            }
        },


        async clearCache(file) {
            file.busy = true;
            const response = (await axios.post('/admin/denny/clear_file_cache', {
                enterpriseId: this.enterpriseId,
                conceptId: this.conceptId,
                filePath: file.filePath,
            })).data;

            file.busy = false;
            if(response.ok) {

                this.filesList.splice(this.filesList.findIndex(x => {
                    return file.fileName === x.fileName;
                }), 1, response.file);
                alert("Cache cleared");

                // this.filesList.splice(this.filesList.findIndex(x => {
                //     return file.fileName === x.fileName;
                // }), 1);
            } else {
                alert("PROBLEM : " + response.error)
            }
        },
    },

    watch: {
        enterpriseId(value) {
            this.loadFilesList()
        }
    },

    computed: {
        filesListFiltered() {
            if(this.showImported) {
                return this.filesList;
            } else {
                return this.filesList.filter(x => {
                    return x.status !== "imported";
                });
            }
        }
    }
}
</script>

<style scoped lang="scss">
.files-list {
    margin-top:10px;
}

.new-files {
    margin-top:10px;
}

.file-wrapper {
    margin-bottom: 10px;
}
table.missing-mapping-table {
    td {
        padding: 1px;

        &.type {
            width: 10%;
        }

        &.record-type {
            width: 10%;
        }
        &.active {
            width: 10%;
        }
        &.input {
            width: 30%;
        }
        &.output {
            width: 30%;
        }
        &.db-cr {
            width: 10%;
        }
    }
}

tr.quick-list-unbalanced {
    background-color: #ff6666;
    color:yellow;
}

tr:nth-of-type(odd).quick-list-unbalanced {
    background-color: #ff4444;
}
</style>
