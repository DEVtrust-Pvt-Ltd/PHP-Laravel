<template>
<div class="list-wrapper" :class="extraClass">
    <div class="card">
        <div class="card-header">
            <a href="/admin/hierarchy/concepts">Concepts</a> &gt; {{pageTitle}}
        </div>

        <div class="card-body">
            <div v-if="'' !== alert" class="alert alert-success" role="alert">
                {{ alert }}
            </div>

            <ul class="nav nav-tabs">
                <li class="nav-item" v-for="(tab, key) in tabs">
                    <a class="nav-link" :href="'#'+key" :class="(tab.active ? 'active ' : '') + (tab.disabled ? 'disabled ' : '')" v-text="tab.title" @click="tabSelected(tab, key)"></a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="concept-general" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.general.active">
                    <entity-edit-form ref='entity_form' v-bind:fields="conceptFields" v-bind:item="concept"></entity-edit-form>
                    <div class="row">
                        <div class="col">
                            <button class="btn btn-primary" @click="saveItem();">Save</button>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade show active" id="concept-departments" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.departments.active">
                    <concept-departments-list :concept-id="concept.id" ref="departments_list"></concept-departments-list>
                </div>
                <div class="tab-pane fade show active" id="concept-sub-departments" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.sub_departments.active">
                    <concept-sub-departments-list :concept-id="concept.id" ref="sub_departments_list"></concept-sub-departments-list>
                </div>
            </div>
        </div>
    </div>

</div>

</template>

<script>
import EntitiesEdit from "../EntitiesEdit";
import ConceptDepartmentsList from "./ConceptDepartmentsList";
import ConceptSubDepartmentsList from "./ConceptSubDepartmentsList";

export default {
    name: "ConceptsEdit",
    components: {ConceptDepartmentsList, ConceptSubDepartmentsList},
    extends: EntitiesEdit,

    data() {
        return {
            item: {
                id: null
            },

            tabs: {
                general: {
                    title: "General",
                    active: true,
                    disabled:false,
                    titleAddOn: "General Info",
                },

                departments: {
                    title: "Departments",
                    active: false,
                    disabled: true,
                    titleAddOn: "Departments",
                },

                sub_departments: {
                    title: "Sub Departments",
                    active: false,
                    disabled: true,
                    titleAddOn: "Sub Departments",
                }
            },

            currentTab: "general",

            conceptFields: [
                "code",
                "name",
                {
                    name: "avgEmployees",
                    title: "Avg Employees",
                    type: "number",
                },
            ],

            concept: {
                id: 0,
            }

        }
    },

    created() {
        window.onpopstate = event => {
            this._checkHash(document.location.hash, "popstate");
        };

        // window.onhashchange = event => {
        //     this._checkHash(document.location.hash, "hashchange");
        // };

    },

    mounted() {
        axios.get("/admin/hierarchy/api/concepts/" + this.id).then(response => {
// console.log(response.data);
            this.concept = response.data;
            this.$refs.entity_form.$forceUpdate();

            if(this.concept.id) {
                this.tabs.departments.disabled = false;
                this.tabs.sub_departments.disabled = false;
            }

            this._checkHash(document.location.hash, "mounted");
        });
    },

    computed: {
        pageTitle() {
            let title = "";
            if(!this.concept.id) {
                title += "Create New Concept";
            } else {
                title += "Edit Concept #" + this.concept.id + " [" + this.concept.name + "]";
            }

            if(this.tabs[this.currentTab].titleAddOn) {
                title += " > " + this.tabs[this.currentTab].titleAddOn;
            }

            return title;
        }
    },

    methods: {
        saveItem() {
            console.log(this.concept);

            const _originalId = this.concept.id;
            let _urlId = _originalId;
            if(0 === _urlId) _urlId = "new";

            axios.post("/admin/hierarchy/api/concepts/" + _urlId, this.concept).then(response => {
                this.concept = response.data.entity;
                if(this.concept.id) {
                    this.tabs.departments.disabled = false;
                    this.tabs.sub_departments.disabled = false;

                    if(0 === _originalId) {
                        history.replaceState(null, null,"/admin/hierarchy/concepts/" + this.concept.id + "#genereal" );
                    }
                }
                alert("Saved OK!");
                // console.log(response.data);
            });

            // alert("TBD!!");
        },

        tabSelected(tab, key) {
            this.currentTab = key;
        },

        _checkHash(hash, s) {
            const split = hash.split("/");
            const _tab = split[0];

            const _checkListTab = (tab, _id, refName) => {
                if(this.currentTab !== tab) {
                    this.currentTab = tab;
                }

                this.$nextTick(() => {

                    const _list = this.$refs[refName];

                    if(_id) {
                        const _showForm = () => {
                            if(_id === "new") {
                                _list.addItem();
                            } else {
                                _list.editingIndex = _list.list.findIndex(x => "" + x.id === _id);
                            }
                        };

                        if(_list.list.length) {
                            _showForm();
                        } else {
                            _list.$once("data:loaded", data => {
                                _showForm();
                            });
                        }

                    } else {
                        _list.editingIndex = null;
                    }
                });
            };


            switch(_tab) {
                case "#departments":
                    _checkListTab("departments", split[1], "departments_list");
                    break;

                case "#sub_departments":
                    _checkListTab("sub_departments", split[1], "sub_departments_list");
                    break;

                case "#general":
                default:
                    if(this.currentTab !== "general") {
                        this.currentTab = "general";
                    }
                    break;
            }
        }
    },

    watch: {
        currentTab(value) {
            for(let k in this.tabs) {
                this.tabs[k].active = k === value;
            }
            // this.tabs.forEach((x, k) => x.active = value === k)
        }
    }
}
</script>

<style scoped>

</style>
