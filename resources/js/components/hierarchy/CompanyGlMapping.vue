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
                <th>Type</th>
                <th>Record Type</th>
                <th>Active</th>
                <th>Input</th>
                <th>Output</th>
                <th>DB/CR</th>
                <th>Override</th>
            </tr>

            <tr class="filter-row">
                <th class="type"><input type="text" class="form-control form-control-sm" v-model="filter.type" /></th>
                <th class="record-type"><select class="form-control form-control-sm" v-model="filter.record_type"><option value="__all__">- - all - -</option><option v-for="rt in recordTypes" :value="rt" v-text="'' === rt ? '[empty]': rt"></option></select></th>
                <th class="active"></th>
                <th class="input"><input type="text" class="form-control form-control-sm" v-model="filter.input" /></th>
                <th class="output"><input type="text" class="form-control form-control-sm" v-model="filter.output" /></th>
                <th class="debit-credit"><select class="form-control form-control-sm" v-model="filter.debit_credit"><option value="__all__">- - all - -</option><option value="debit">DB</option><option value="credit">CR</option></select></th>
<!--                <th class="description"><input type="text" class="form-control form-control-sm" v-model="filter.description" /></th>-->
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
            <tr v-for="item in listFiltered">
                <td>
<!--                    <input v-if="overrideCheck[idx]" type="text" class="form-control form-control-sm" v-model="item.type" />-->
<!--                    <div v-else class="read-only-cell" v-text="item.type"></div>-->

                    <div class="read-only-cell" v-text="item.type"></div>
                </td>
                <td>
<!--                    <select v-if="overrideCheck[idx]" class="form-control form-control-sm" v-model="item.record_type"><option v-for="rt in recordTypes" :value="rt" v-text="rt"></option></select>-->
<!--                    <div v-else class="read-only-cell" v-text="item.record_type"></div>-->

                    <div class="read-only-cell" v-text="item.record_type"></div>
                </td>
                <td class="text-center">
                    <input  v-if="overrideCheck[item.full_idx]" type="checkbox" class="form-control form-control-sm" v-model="item.active" />
                    <div v-else class="read-only-cell">{{ item.active ? "Yes" : "&nbsp;" }}</div>
                </td>
                <td>
<!--                    <input v-if="overrideCheck[idx]" type="text" class="form-control form-control-sm" v-model="item.input" />-->
<!--                    <div v-else class="read-only-cell" v-text="item.input"></div>-->

                    <div class="read-only-cell" v-text="item.input"></div>
                </td>
                <td>
                    <input v-if="overrideCheck[item.full_idx]" type="text" class="form-control form-control-sm" v-model="item.output" :disabled="!item.active" />
                    <div v-else class="read-only-cell" v-text="item.output"></div>
                </td>
                <td>
<!--                    <select v-if="overrideCheck[idx]" class="form-control form-control-sm" v-model="item.debit_credit" :disabled="!item.active"><option value="debit">DB</option><option value="credit">CR</option></select>-->
<!--                    <div v-else class="read-only-cell">{{ "credit" === item.debit_credit ? "CR" : "DB" }}</div>-->
                    <div class="read-only-cell">{{ "credit" === item.debit_credit ? "CR" : "DB" }}</div>
                </td>
                <td class="text-center">
                    <input class="form-control form-control-sm" type="checkbox" v-model="overrideCheck[item.full_idx]" />
<!--                    <a href="#" class="delete-link" title="Delete" @click="deleteItem(item, idx)">-->
<!--                        <svg class="bi bi-x" width="2em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">-->
<!--                            <path fill-rule="evenodd" d="M11.854 4.146a.5.5 0 010 .708l-7 7a.5.5 0 01-.708-.708l7-7a.5.5 0 01.708 0z" clip-rule="evenodd"/>-->
<!--                            <path fill-rule="evenodd" d="M4.146 4.146a.5.5 0 000 .708l7 7a.5.5 0 00.708-.708l-7-7a.5.5 0 00-.708 0z" clip-rule="evenodd"/>-->
<!--                        </svg>-->
<!--                    </a>-->
                </td>
            </tr>
        </tbody>
        </table>
        <div class="buttons">
            <div class="float-left">
<!--                <button class="btn btn-info btn-sm" @click="addRow()">Add Mapping Row</button>-->
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
    name: "CompanyGlMapping",
    components: {SelectField},
    props: ["enterpriseId", "companyId"],
    data() {
        return {
            selectedConceptId: "",
            concepts: [],
            list: [],
            override: [],
            overrideCheck: [],
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
                list: this.overrideCheck.map((ch, idx) => {
                    if(!ch) return false;

                    const found = this.override.find(o => {
                        return o.input === this.list[idx].input;
                    });

                    if(!found) return false;

                    if(
                        found.output === this.list[idx].output
                        && !!found.active === !!this.list[idx].active
                    ) return false;

                    return found;
                }).filter(x => x),
            };

// console.log(request);return;
            const response = (await axios.post("/admin/hierarchy/companies/" + this.companyId + "/gl_mapping", request)).data;
// console.log(response);
            if(response.ok) {
                // this.override = response.list;
                alert("Saved OK");
            } else {
                alert("Problem Saving");
            }
        },

        async initiateList() {
        },

        clearFilter() {
            this.filter.type = null;
            this.filter.record_type = '__all__';
            this.filter.active = null;
            this.filter.input = null;
            this.filter.output = null;
            this.filter.debit_credit =  '__all__';
            this.filter.description = null;
        },

        makeOverrideCheckFiltered() {

        }

    },

    watch: {
        async selectedConceptId() {
            this.list =  (await axios.get("/admin/hierarchy/enterprises/" + this.enterpriseId + "/gl_mapping?concept_id="+this.selectedConceptId)).data;
            this.override =  (await axios.get("/admin/hierarchy/companies/" + this.companyId + "/gl_mapping?concept_id="+this.selectedConceptId)).data;

            this.overrideCheck = this.list.map(x => {
                return 0 <= this.override.findIndex(o => x.input === o.input);
            });

            this.makeOverrideCheckFiltered();

        },

        overrideCheckFiltered(x, y) {
            console.log(x, y);
        }
    },
    computed: {
        displayList() {
            return this.list.map((item, idx) => {
                if(!this.overrideCheck[idx]) return item;

                const found = this.override.find(x => {
                    return item.input === x.input;
                });

                if(found) return found;

                const copy = {};
                for(let key in item) {
                    copy[key] = item[key];
                }
                copy.id = "new";

                this.override.push(copy);
                return copy;

            });
        },

        listFiltered() {
            return this.displayList.filter((x, idx) => {
                x.full_idx = idx;

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

.read-only-cell {
    padding: 3px;
}
</style>
