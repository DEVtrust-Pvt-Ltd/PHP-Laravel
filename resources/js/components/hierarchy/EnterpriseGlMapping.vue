<template>
<div class="gl-mapping-wrapper">
    <div class="row">
        <div class="col text-right">
            <label for="concept_select">Concept: </label>
            <select-field v-if="concepts.length > 1" id="concept_select" v-model="selectedConceptId" v-bind:params="{data: concepts, }" extra-class="form-control form-control-sm"></select-field>
            <span v-else-if="1 === concepts.length">{{ concepts[0].title }}</span>
            <span v-else>No Concepts Assigned</span>
        </div>
    </div>

    <div class="mapping-list" v-if="0 < concepts.length">
        <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="type">Type</th>
                <th class="record-type">Record Type</th>
                <th class="active">Active</th>
                <th class="input">Input</th>
                <th class="output">Output</th>
                <th class="debit-credit">DB/CR</th>
                <th class="description">Description</th>
                <th class="controls">&nbsp;</th>
            </tr>
            <tr class="filter-row">
                <th class="type"><input type="text" class="form-control form-control-sm" v-model="filter.type" /></th>
                <th class="record-type"><select class="form-control form-control-sm" v-model="filter.record_type"><option value="__all__">- - all - -</option><option v-for="rt in recordTypes" :value="rt" v-text="'' === rt ? '[empty]': rt"></option></select></th>
                <th class="active"></th>
                <th class="input"><input type="text" class="form-control form-control-sm" v-model="filter.input" /></th>
                <th class="output"><input type="text" class="form-control form-control-sm" v-model="filter.output" /></th>
                <th class="debit-credit"><select class="form-control form-control-sm" v-model="filter.debit_credit"><option value="__all__">- - all - -</option><option value="debit">DB</option><option value="credit">CR</option></select></th>
                <th class="description"><input type="text" class="form-control form-control-sm" v-model="filter.description" /></th>
                <th class="controls">
                    <a href="javascript:void(0);" class="delete-link" title="Clear Filter" @click="clearFilter()">
                        <svg class="bi bi-x" width="2em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M11.854 4.146a.5.5 0 010 .708l-7 7a.5.5 0 01-.708-.708l7-7a.5.5 0 01.708 0z" clip-rule="evenodd"/>
                            <path fill-rule="evenodd" d="M4.146 4.146a.5.5 0 000 .708l7 7a.5.5 0 00.708-.708l-7-7a.5.5 0 00-.708 0z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(item, idx) in listFiltered">
                <td class="type"><input type="text" class="form-control form-control-sm" v-model="item.type" /></td>
                <td class="record-type"><select class="form-control form-control-sm" v-model="item.record_type"><option v-for="rt in recordTypes" :value="rt" v-text="rt"></option></select></td>
                <td class="text-center active"><input type="checkbox" class="form-control form-control-sm" v-model="item.active" /></td>
                <td class="input"><input type="text" class="form-control form-control-sm" v-model="item.input" @change="inputChanged(item, idx, $event)" /></td>
                <td class="output"><input type="text" class="form-control form-control-sm" v-model="item.output" :disabled="!item.active" /></td>
                <td class="debit-credit"><select class="form-control form-control-sm" v-model="item.debit_credit" :disabled="!item.active"><option value="debit">DB</option><option value="credit">CR</option></select></td>
                <td class="description"><input type="text" class="form-control form-control-sm" v-model="item.description" /></td>
                <td class="text-center controls">
                    <a href="javascript:void(0);" class="delete-link" title="Delete" @click="deleteItem(item, idx)">
                        <svg class="bi bi-x" width="2em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M11.854 4.146a.5.5 0 010 .708l-7 7a.5.5 0 01-.708-.708l7-7a.5.5 0 01.708 0z" clip-rule="evenodd"/>
                            <path fill-rule="evenodd" d="M4.146 4.146a.5.5 0 000 .708l7 7a.5.5 0 00.708-.708l-7-7a.5.5 0 00-.708 0z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </td>
            </tr>
        </tbody>
        </table>
        <div class="buttons">
            <div class="float-left">
                <button class="btn btn-info btn-sm" @click="addRow()">Add Mapping Row</button>
            </div>
            <div class="float-right">
                <button class="btn btn-primary btn-sm" @click="save()">Save</button>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import SelectField from "../form-fields/SelectField";
export default {
    name: "EnterpriseGlMapping",
    components: {SelectField},
    props: ["enterpriseId"],
    data() {
        return {
            selectedConceptId: "",
            concepts: [],
            list: [],
            recordTypes: [
                "", "FCLO", "FCOM", "FFIN", "FSAL", "FTEN", "FADJ", "FDEP", "FSFT", "FMSL", "FCPR",
            ],
            filter: {
                type: null,
                record_type: '__all__',
                active: null,
                input: null,
                output: null,
                debit_credit: '__all__',
                description: null,
            }
        };
    },

    async mounted() {
        this.concepts = (await axios.get("/admin/hierarchy/api/list/concepts/" + this.enterpriseId)).data;
        if(0 < this.concepts.length) {
            this.selectedConceptId = this.concepts[0].value;
            // this.initiateList();
        }
// console.log(this.concepts);
    },

    methods: {
        addRow() {
            this.list.push({
                id: "new",
                active: true,
                type: "DSR",
                debit_credit: "credit",
            });
        },

        deleteItem(item, idx) {
            if(confirm("Are you sure?")) {
                this.list.splice(idx, 1);
            }
        },

        async save() {
            const request = {
                conceptId: this.selectedConceptId,
                list: this.list.map(x => {
                    delete(x.old_input);
                    return x;
                }),
            };

            const response = (await axios.post("/admin/hierarchy/enterprises/" + this.enterpriseId + "/gl_mapping", request)).data;
// console.log(response);
            if(response.ok) {
                this.list = response.list;
                alert("Saved OK");
            } else {
                alert("Problem Saving");
            }
        },

        async initiateList() {
        },

        inputChanged(item, idx, event) {
            if(item.old_input === item.description) {
                item.description = item.input;
            }
            item.old_input = item.input;
        },

        clearFilter() {
            this.filter.type = null;
            this.filter.record_type = '__all__';
            this.filter.active = null;
            this.filter.input = null;
            this.filter.output = null;
            this.filter.debit_credit =  '__all__';
            this.filter.description = null;
        }
    },

    computed: {
        listFiltered() {
            return this.list.filter(x => {
                const type = !this.filter.type || 0 <= x.type.toLowerCase().indexOf(this.filter.type.toLowerCase());
                const record_type = '__all__' === this.filter.record_type || x.record_type === this.filter.record_type;
                const active = true;
                const input = !this.filter.input || 0 <= x.input.toLowerCase().indexOf(this.filter.input.toLowerCase());
                const output = !this.filter.output || 0 <= x.output.toLowerCase().indexOf(this.filter.output.toLowerCase());
                const debit_credit = '__all__' === this.filter.debit_credit || x.debit_credit === this.filter.debit_credit;
                const description = !this.filter.description || 0 <= x.description.toLowerCase().indexOf(this.filter.description.toLowerCase());
                return type && record_type && active && input && output && debit_credit && description;
            });
        }
    },

    watch: {
        async selectedConceptId() {
            this.list =  (await axios.get("/admin/hierarchy/enterprises/" + this.enterpriseId + "/gl_mapping?concept_id="+this.selectedConceptId)).data.map(x => {
                x.old_input = x.input;
                return x;
            });
        },
    }
}
</script>

<style scoped>
.gl-mapping-wrapper {
    margin: 10px;
}

#concept_select {
    width: auto;
    display: inline-block;
}

.delete-link {
    color:red;
}

.mapping-list table tbody td {
    padding: 0.5rem;
}

td.type, th.type {
    width: 8%;
}
td.active, th.active {
    width: 5%;
}

.filter-row {
    background-color: olive;
}
</style>
