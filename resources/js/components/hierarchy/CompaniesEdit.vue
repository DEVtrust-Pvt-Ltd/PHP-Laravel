<template>
<div class="list-wrapper" :class="extraClass">
    <div class="card">
        <div class="card-header">
            <a href="/admin/hierarchy/companies">Companies</a> &gt; {{pageTitle}}
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
                <div class="tab-pane fade show active" id="enterprise-general" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.general.active">
                    <div v-if="auth.isMaster">
                        <entity-edit-form ref='entity_form' v-bind:fields="companyFields" v-bind:item="company"></entity-edit-form>
                        <div class="row">
                            <div class="col">
                                <button class="btn btn-primary" @click="saveItem();">Save</button>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <company-general-view :item="company" ref="entity_form"></company-general-view>
                    </div>
                </div>

                <div class="tab-pane fade show active" id="company-notes" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.notes.active">
                    <company-notes-list :company-id="company.id" ref="notes_list"></company-notes-list>
                </div>
                <div class="tab-pane fade show active" id="company-dms" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.dms.active">
                    <company-dms-list :company-id="company.id" ref="dms_list"></company-dms-list>
                </div>
                <div class="tab-pane fade show active" id="company-locations" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.locations.active">
                    <company-locations-list :company-id="company.id" ref="locations_list"></company-locations-list>
                </div>
                <div class="tab-pane fade show active" id="company-services" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.services.active">
                    <company-services :company-id="company.id" ref="services_list"></company-services>
                </div>
                <div class="tab-pane fade show active" id="company-gl-mapping" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.gl_mapping.active">
                    <company-gl-mapping :enterprise-id="company.enterprise_id" :company-id="company.id" ref="gl_mapping_list"></company-gl-mapping>
                </div>
                <div class="tab-pane fade show active" id="company-organizations" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.organizations.active">
                    <company-organizations :enterprise-id="company.enterprise_id" :company-id="company.id" ref="organizations"></company-organizations>
                </div>
                <div class="tab-pane fade show active" id="company-documents" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.documents.active">
                    <company-documents-list :enterprise-id="company.enterprise_id" :company-id="company.id" ref="documents"></company-documents-list>
                </div>
                <div class="tab-pane fade show active" id="company-vendors" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.vendors.active">
                    <company-vendors-list :enterprise-id="company.enterprise_id" :company-id="company.id" ref="vendors"></company-vendors-list>
                </div>
                <div class="tab-pane fade show active" id="company-other" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.other.active">
                    <company-other-data :enterprise-id="company.enterprise_id" :company-id="company.id" ref="other"></company-other-data>
                </div>
                <div class="tab-pane fade show active" id="company-third-party" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.third_party.active">
                    <third-party-vendors-tab level="company" :entity-id="company.id" ref="third_party"></third-party-vendors-tab>
                </div>

            </div>
        </div>
    </div>

</div>

</template>

<script>
import EntitiesEdit from "../EntitiesEdit";
import CompanyNotesList from "./CompanyNotesList";
import CompanyDmsList from "./CompanyDmsList";
import CompanyLocationsList from "./CompanyLocationsList";

import statesList from "../../tools/states";
import CompanyServices from "./CompanyServices";
import CompanyGlMapping from "./CompanyGlMapping";
import CompanyGeneralView from "./CompanyGeneralView";
import CompanyOrganizations from "./CompanyOrganizations";
import CompanyDocumentsList from "./CompanyDocumentsList";
import CompanyVendorsList from "./CompanyVendorsList";
import CompanyOtherData from "./CompanyOtherData";
import ThirdPartyVendorsTab from "./ThirdPartyVendorsTab";

export default {
    name: "CompaniesEdit",
    components: {
        ThirdPartyVendorsTab,
        CompanyOtherData,
        CompanyVendorsList,
        CompanyDocumentsList,
        CompanyOrganizations,
        CompanyGeneralView,
        CompanyGlMapping,
        CompanyServices,
        CompanyNotesList,
        CompanyDmsList,
        CompanyLocationsList
    },
    extends: EntitiesEdit,

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
                    title: "General",
                    active: true,
                    disabled:false,
                    titleAddOn: "General Info",
                },

                notes: {
                    title: "Notes",
                    active: false,
                    disabled: true,
                    titleAddOn: "Notes",
                    hidden: !this.auth.isMaster,
                },

                dms: {
                    title: "District Managers",
                    active: false,
                    disabled: true,
                    titleAddOn: "DMs",
                },

                locations: {
                    title: "Locations",
                    active: false,
                    disabled: true,
                    titleAddOn: "Locations",
                },

                services: {
                    title: "Services",
                    active: false,
                    disabled: true,
                    titleAddOn: "Services",
                    hidden: !this.auth.isMaster,
                },

                gl_mapping: {
                    title: "GL Mapping",
                    active: false,
                    disabled: true,
                    titleAddOn: "GL Mapping",
                },

                organizations: {
                    title: "Organizations",
                    active: false,
                    disabled: true,
                    titleAddOn: "Organizations",
                    hidden: !this.auth.isMaster,
                },

                documents: {
                    title: "Documents",
                    active: false,
                    disabled: true,
                    titleAddOn: "Documents",
                    hidden: !this.auth.isMaster,
                },
                vendors: {
                    title: "Vendors",
                    active: false,
                    disabled: true,
                    titleAddOn: "Vendors",
                    hidden: !this.auth.isMaster,
                },
                other: {
                    title: "Other",
                    active: false,
                    disabled: true,
                    titleAddOn: "Other",
                    hidden: !this.auth.isMaster,
                },
                third_party: {
                    title: "3rd Party",
                    active: false,
                    disabled: true,
                    titleAddOn: "3rd Party Vendors Info",
                    hidden: !this.auth.isMaster,
                },

            },

            currentTab: "general",

            companyFields: [
                {
                    name: "enterprise_id",
                    title: "Enterprise",
                    type: "select",
                    list: {
                        url: "/admin/hierarchy/api/list/enterprises",
                    }
                },
                "code",
                "name",
                {
                    name: "ein",
                    title: "EIN",
                },
/*
                {
                    name: "services",
                    type: "multiselect",
                    list: {
                        url: "/admin/hierarchy/api/list/services"
                    }
                },
*/
                "address1",
                "address2",
                "city",
                {
                    name: "state",
                    type: "select",
                    list: {
                        data: statesList.map(x => {
                            return {
                                value: x.abbreviation,
                                title: x.name,
                            }
                        }),
                    }
                },
                "zip",
                {
                    name: "impound",
                    type: "boolean",
                },
                {
                    name: "org_levels",
                    title: "Org Levels",
                    type: "select",
                    list: {
                        data: [
                            {value: 3, title: "3"},
                            {value: 4, title: "4"},
                        ]
                    }
                },
                "misc1",
                "misc2",
                "misc3",
                {
                    name: "intacct_template_type",
                    title: "Intacct Template Type",
                    type: "select",
                    list: {
                        data: [
                            {value: 1, title: "1"},
                            {value: 2, title: "2"},
                        ]
                    }
                },
                "intacct_company_id",
          ],
            company: {
                id: 0,
                services: [],
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
        axios.get("/admin/hierarchy/api/companies/" + this.id).then(response => {
// console.log(response.data);
            this.company = response.data;
            this.$refs.entity_form.$forceUpdate();

            if(this.company.id) {
                this.tabs.notes.disabled = false;
                this.tabs.dms.disabled = false;
                this.tabs.locations.disabled = false;
                this.tabs.services.disabled = false;
                this.tabs.gl_mapping.disabled = false;
                this.tabs.organizations.disabled = false;
                this.tabs.documents.disabled = false;
                this.tabs.vendors.disabled = false;
                this.tabs.other.disabled = false;
                this.tabs.third_party.disabled = false;
            }

            this._checkHash(document.location.hash, "mounted");
        });
    },

    computed: {
        pageTitle() {
            let title = "";
            if(!this.company.id) {
                title += "Create New Company";
            } else {
                title += "Edit Company #" + this.company.id + " [" + this.company.name + "]";
            }

            if(this.tabs[this.currentTab].titleAddOn) {
                title += " > " + this.tabs[this.currentTab].titleAddOn;
            }

            return title;
        }
    },

    methods: {
        saveItem() {
            const _originalId = this.company.id;
            let _urlId = _originalId;
            if(0 === _urlId) _urlId = "new";

            axios.post("/admin/hierarchy/api/companies/" + _urlId, this.company).then(response => {
                this.company = response.data.entity;
                if(this.company.id) {
                    this.tabs.notes.disabled = false;
                    this.tabs.dms.disabled = false;
                    this.tabs.locations.disabled = false;
                    this.tabs.services.disabled = false;
                    this.tabs.gl_mapping.disabled = false;
                    this.tabs.organizations.disabled = false;
                    this.tabs.documents.disabled = false;
                    this.tabs.vendors.disabled = false;
                    this.tabs.other.disabled = false;
                    this.tabs.third_party.disabled = false;

                    if(0 === _originalId) {
                        history.replaceState(null, null,"/admin/hierarchy/companies/" + this.company.id + "#genereal" );
                    }
                }
                alert("Saved OK!");
                // console.log(response.data);
            });
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
                case "#notes":
                    _checkListTab("notes", split[1], "notes_list");
                    break;

                case "#dms":
                    _checkListTab("dms", split[1], "dms_list");
                    break;

                case "#locations":
                    _checkListTab("locations", split[1], "locations_list");
                    break;

                case "#documents":
                    _checkListTab("documents", split[1], "documents_list");
                    break;

                case "#vendors":
                    _checkListTab("vendors", split[1], "vendors_list");
                    break;

                case "#services":
                    if(this.currentTab !== "services") {
                        this.currentTab = "services";
                    }
                    break;

                case "#gl_mapping":
                    if(this.currentTab !== "gl_mapping") {
                        this.currentTab = "gl_mapping";
                    }
                    break;

                case "#other":
                    if(this.currentTab !== "other") {
                        this.currentTab = "other";
                    }
                    break;

                case "#third_party":
                    if(this.currentTab !== "third_party") {
                        this.currentTab = "third_party";
                    }
                    break;

                case "#organizations":
                    if(this.currentTab !== "organizations") {
                        this.currentTab = "organizations";
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
