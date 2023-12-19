<template>
<div class="row file-wrapper" :class="file.error === '' ? 'ok' : file.error">
    <div class="col-6 file-info"><b>[{{file.enterpriseIdentifier}}]</b> {{file.fileName}}
        <span v-if="file.error !== ''"> [{{file.error}}]</span>
        <span v-if="file.status === 'imported'"> [imported]</span>
    </div>
    <div class="col-6 file-controls">
        <div class="ok-container text-right" v-if="!file.busy && file.error === ''">
            <div class="btn-group btn-group-sm" role="group">
                <button class="btn btn-sm btn-secondary" @click="quickBalanceList()" :disabled="file.busy" v-show="!file.busy">Quick List</button>
                <button  class="btn btn-sm btn-primary" @click="importFile()" :disabled="file.busy">Import</button>

                <div class="btn-group btn-group-sm" role="group">
                    <button :id="'other_' + file.fileName" type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" :disabled="file.busy">
                        More
                    </button>
                    <div class="dropdown-menu" :aria-labelledby="'other_' + file.fileName">
                        <a class="dropdown-item" href="javascript:void()" :disabled="file.busy" @click="checkBalance()">Check</a>
                        <a class="dropdown-item" href="javascript:void()" :disabled="file.busy" @click="rawData()">Raw Data</a>
                        <a class="dropdown-item" href="javascript:void()" :disabled="file.busy" @click="clearCache()">Clear Cache</a>
                        <a v-if="file.status === 'imported'" class="dropdown-item" href="javascript:void()" :disabled="file.busy" @click="rollbackImport()">Rollback Import</a>
                        <a class="dropdown-item" target="_blank" :href="'/admin/hierarchy/enterprises/' + file.enterpriseId + '#gl_mapping'" :disabled="file.busy">Mapping</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="missing-mapping-container text-right" v-else-if="!file.busy && file.error === 'missing_mapping'">
            <div class="btn-group btn-group-sm" role="group">
                <button class="btn btn-sm btn-primary" @click="addMissingMapping()" :disabled="file.busy">Add Missing Mapping</button>

                <div class="btn-group btn-group-sm" role="group">
                    <button :id="'other_' + file.fileName" type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" :disabled="file.busy">
                        More
                    </button>
                    <div class="dropdown-menu" :aria-labelledby="'other_' + file.fileName">
                        <a class="dropdown-item" href="javascript:void()" :disabled="file.busy" @click="rawData()">Raw Data</a>
                        <a class="dropdown-item" href="javascript:void()" :disabled="file.busy" @click="clearCache()">Clear Cache</a>

                        <a v-if="file.status === 'imported'" class="dropdown-item" href="javascript:void()" :disabled="file.busy" @click="rollbackImport()">Rollback Import</a>

                        <a class="dropdown-item" target="_blank" :href="'/admin/hierarchy/enterprises/' + file.enterpriseId + '#gl_mapping'" :disabled="file.busy">Mapping</a>

                    </div>
                </div>
            </div>
        </div>
        <div class="unbalanced-container text-right" v-else-if="!file.busy && file.error === 'unbalanced'">
            <div class="btn-group btn-group-sm" role="group">
                <button class="btn btn-sm btn-primary" @click="quickBalanceList()" :disabled="file.busy" v-show="!file.busy">Quick List</button>
                <button class="btn btn-sm btn-secondary" @click="checkBalance()" :disabled="file.busy" v-show="!file.busy">Check</button>

                <div class="btn-group btn-group-sm" role="group">
                    <button :id="'other_' + file.fileName" type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" :disabled="file.busy">
                        More
                    </button>
                    <div class="dropdown-menu" :aria-labelledby="'other_' + file.fileName">
                        <a class="dropdown-item" href="javascript:void()" :disabled="file.busy" @click="forceImport()">Force Import</a>
                        <a class="dropdown-item" href="javascript:void()" :disabled="file.busy" @click="rawData()">Raw Data</a>
                        <a class="dropdown-item" href="javascript:void()" :disabled="file.busy" @click="clearCache()">Clear Cache</a>

                        <a v-if="file.status === 'imported'" class="dropdown-item" href="javascript:void()" :disabled="file.busy" @click="rollbackImport()">Rollback Import</a>

                        <a class="dropdown-item" target="_blank" :href="'/admin/hierarchy/enterprises/' + file.enterpriseId + '#gl_mapping'" :disabled="file.busy">Mapping</a>

                    </div>
                </div>
            </div>
<!--            <button class="btn btn-sm btn-danger" @click="forceImport()" :disabled="file.busy" v-show="!file.busy">Force Import</button>-->
<!--            <button class="btn btn-sm btn-info" @click="toggleBusy()">Toggle {{file.busy}}</button>-->
        </div>
        <div class="file-loading" v-if="file.busy"><img src="/img/loading.gif" /></div>
    </div>
</div>

</template>

<script>
export default {
    name: "DennySingleFile",
    props: ["file"],

    methods: {
        importFile() {
            this.$parent.importFile(this.file);
        },

        addMissingMapping() {
            this.$parent.addMissingMapping(this.file);
        },

        checkBalance() {
            this.$parent.checkBalance(this.file);
        },

        quickBalanceList() {
            this.$parent.quickBalanceList(this.file);
        },

        forceImport() {
            if(confirm("Are you sure?")) {
                this.$parent.importFile(this.file);
            }
        },

        rawData() {
            this.$parent.rawData(this.file);
        },

        rollbackImport() {
            this.$parent.rollbackImport(this.file);
        },

        clearCache() {
            this.$parent.clearCache(this.file);
        },

        // toggleBusy() {
        //     this.file.busy = !this.file.busy;
        //     console.log(this.file.busy);
        // }

    }
}
</script>

<style scoped lang="scss">
.file-loading {
    display: inline-block;
    height: 28px;
    img {
        height: 100%;
    }
}

.file-wrapper {
    padding: 5px 0;
    &.ok {
        background-color: lightgreen;
    }
}
</style>
