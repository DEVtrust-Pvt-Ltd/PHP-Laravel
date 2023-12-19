<template>
<div class="list-wrapper" :class="extraClass">
    <div class="card">
        <div class="card-header">
            <a href="/admin/employees">Employees</a> &gt; {{pageTitle}}
        </div>

        <div class="card-body">
            <div v-if="'' !== alert" class="alert alert-success" role="alert">
                {{ alert }}
            </div>

            <ul class="nav nav-tabs">
                <li class="nav-item" v-for="(tab, key) in tabs" v-show="!tab.hidden">
                    <a class="nav-link" :href="'#'+key" :class="(tab.active ? 'active ' : '') + (tab.disabled ? 'disabled ' : '')" v-text="tab.title" @click="tabSelected(tab, key)"></a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="employee-general" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.general.active">
                    <div v-if="auth.isMaster">
                        <employee-general-edit ref="general_edit" v-bind:item="employee"></employee-general-edit>
<!--                        <entity-edit-form ref='entity_form' v-bind:fields="companyFields" v-bind:item="company"></entity-edit-form>-->
                        <div class="row">
                            <div class="col">
                                <button class="btn btn-primary" @click="saveItem();">Save</button>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <h1>Hola</h1>
                    </div>
                </div>

                <div class="tab-pane fade show active" id="employee-contacts" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.contacts.active">
                    <employee-contacts-list :employee-id="employee.id" ref="contacts_list"></employee-contacts-list>
                </div>
                <div class="tab-pane fade show active" id="employee-certifications" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.certifications.active">
                    <employee-certifications-list :employee-id="employee.id" ref="certifications_list"></employee-certifications-list>
                </div>
                <div class="tab-pane fade show active" id="employee-dd" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.dd.active">
                    <employee-dd-list :employee-id="employee.id" ref="dd_list"></employee-dd-list>
                </div>
                <div class="tab-pane fade show active" id="employee-i9" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.i9.active">
                    <employee-i9-edit :employee-id="employee.id" v-bind:item="employee" ref="i9_list"></employee-i9-edit>
                </div>
                <div class="tab-pane fade show active" id="employee-taxes" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.taxes.active">
                    <employee-taxes-list :employee-id="employee.id" ref="taxes_list"></employee-taxes-list>
                </div>
            </div>
        </div>
    </div>

</div>

</template>

<script>
import EntitiesEdit from "./EntitiesEdit";

import statesList from "../tools/states";
import EmployeeGeneralEdit from "./EmployeeGeneralEdit";
import EmployeeContactsList from "./EmployeeContactsList";
import EmployeeCertificationsList from "./EmployeeCertificationsList";
import EmployeeDdList from "./EmployeeDdList";
import EmployeeI9Edit from "./EmployeeI9Edit";
import EmployeeTaxesList from "./EmployeeTaxesList";

export default {
    name: "EmployeesEdit",
    components: {
        EmployeeTaxesList,
        EmployeeI9Edit,
        EmployeeDdList,
        EmployeeContactsList,
        EmployeeGeneralEdit,
        EmployeeCertificationsList,
    },
    extends: EntitiesEdit,

    props: {
        auth: {
            type: Object,
            default: {
                isMaster: false,
            }
        },
        companyId: String,
    },

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

                contacts: {
                    title: "Contacts",
                    active: false,
                    disabled: true,
                    titleAddOn: "Contacts",
                    // hidden: !this.auth.isMaster,
                },

                certifications: {
                    title: "Certifications",
                    active: false,
                    disabled: true,
                    titleAddOn: "Certifications",
                },

                dd: {
                    title: "DD",
                    active: false,
                    disabled: true,
                    titleAddOn: "Direct Deposits",
                },

                i9: {
                    title: "I9",
                    active: false,
                    disabled: true,
                    titleAddOn: "I9",
                    // hidden: !this.auth.isMaster,
                },

                taxes: {
                    title: "Taxes",
                    active: false,
                    disabled: true,
                    titleAddOn: "Taxes",
                },
            },

            currentTab: "general",

            company: {
                id: 0,
                services: [],
            },

            employee: {
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

    async mounted() {
        this.company = (await axios.get("/admin/hierarchy/api/companies/" + this.companyId)).data;
        this.employee = (await axios.get("/admin/api/employees/" + this.id + "?companyId=" + this.companyId)).data;
// console.log(response.data);

        this.$refs.general_edit.$forceUpdate();

        if(this.employee.id) {
            this.tabs.contacts.disabled = false;
            this.tabs.certifications.disabled = false;
            this.tabs.dd.disabled = false;
            this.tabs.i9.disabled = false;
            this.tabs.taxes.disabled = false;
        }

        this._checkHash(document.location.hash, "mounted");

    },

    computed: {
        pageTitle() {
            let title = "";
            if(!this.employee.id) {
                title += "Create New Employee [" + this.company.code + "]";
            } else {
                title += "Edit Employee #" + this.employee.id + " \"" + this.employee.first_name + " " + this.employee.last_name + "\" [" + this.company.code + "]";
            }

            if(this.tabs[this.currentTab].titleAddOn) {
                title += " > " + this.tabs[this.currentTab].titleAddOn;
            }

            return title;
        }
    },

    methods: {
        async saveItem() {
            const _originalId = this.employee.id;
            let _urlId = _originalId;
            if(0 === _urlId) _urlId = "new";

            const response = (await axios.post("/admin/api/employees/" + _urlId, this.employee)).data;
            this.employee = response.entity;
            if(this.employee.id) {
                this.tabs.contacts.disabled = false;
                this.tabs.certifications.disabled = false;
                this.tabs.dd.disabled = false;
                this.tabs.i9.disabled = false;
                this.tabs.taxes.disabled = false;

                if(0 === _originalId) {
                    history.replaceState(null, null,"/admin/employees/" + this.employee.id + "#genereal" );
                }
            }
            alert("Saved OK!");
            // console.log(response);
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
                case "#contacts":
                    _checkListTab("contacts", split[1], "contacts_list");
                    break;

                case "#certifications":
                    _checkListTab("certifications", split[1], "certifications_list");
                    break;

                case "#dd":
                    _checkListTab("dd", split[1], "dd_list");
                    break;

                case "#taxes":
                    _checkListTab("taxes", split[1], "taxes_list");
                    break;

                case "#i9":
                    if(this.currentTab !== "i9") {
                        this.currentTab = "i9";
                    }
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
        }
    }
}
</script>

<style scoped>

</style>
