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

    <div class="organizations-list" v-if="0 < concepts.length">
        <div class="wrapper">
            <div class="department-row row" v-for="d in organizations">
                <div class="col">
                    <div class="department">
                        <company-org-override :item="d"></company-org-override>
                    </div>
                    <div class="sub-wrapper">
                        <div class="subdept-row row" v-for="s in d.sub_departments">
                            <div class="col-2 text-center">|__</div>
                            <div class="col-10">
                                <div class="subdept">
                                    <company-org-override :item="s"></company-org-override>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!--        <div class="buttons">-->
<!--            <div class="float-right">-->
<!--                <button class="btn btn-primary btn-sm" @click="save()">Save</button>-->
<!--            </div>-->
<!--        </div>-->
    </div>
</div>
</template>

<script>
import SelectField from "../form-fields/SelectField";
import CompanyOrgOverride from "./CompanyOrgOverride";
export default {
    name: "CompanyOrganizations",
    components: {CompanyOrgOverride, SelectField},
    props: ["enterpriseId", "companyId"],
    data() {
        return {
            selectedConceptId: "",
            concepts: [],

            organizations: [],

            list: [],
            override: [],
            overrideCheck: [],

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

    },

    watch: {
        async selectedConceptId() {

            this.organizations = (await axios.get("/admin/hierarchy/companies/" + this.companyId + "/organizations?concept_id=" + this.selectedConceptId)).data;

            // this.list =  (await axios.get("/admin/hierarchy/enterprises/" + this.enterpriseId + "/gl_mapping?concept_id="+this.selectedConceptId)).data;
            // this.override =  (await axios.get("/admin/hierarchy/companies/" + this.companyId + "/gl_mapping?concept_id="+this.selectedConceptId)).data;

            // this.overrideCheck = this.list.map(x => {
            //     return 0 <= this.override.findIndex(o => x.input === o.input);
            // });

        }
    },
    computed: {
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


.department {
    border-bottom: dotted 1px black;
    padding: 5px;
}

.subdept {
    border-bottom: dotted 1px green;
    padding: 3px;
}

</style>
