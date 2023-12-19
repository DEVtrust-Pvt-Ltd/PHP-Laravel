<template>
<div class="list-wrapper" :class="extraClass">
    <div class="card">
        <div class="card-header">
            <a href="/admin/hierarchy/enterprises">Enterprises</a> &gt; {{pageTitle}}
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
                        <entity-edit-form ref='entity_form' v-bind:fields="enterpriseFields" v-bind:item="enterprise"></entity-edit-form>
                        <div class="row">
                            <div class="col">
                                <button class="btn btn-primary" @click="saveItem();">Save</button>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <enterprise-general-view ref='entity_form' v-bind:item="enterprise"></enterprise-general-view>
                    </div>
                </div>

                <div class="tab-pane fade show active" id="enterprise-contacts" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.contacts.active">
                    <enterprise-contacts-list :enterprise-id="enterprise.id" ref="contacts_list"></enterprise-contacts-list>
                </div>

                <div class="tab-pane fade show active" id="enterprise-gl-mapping" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.gl_mapping.active">
                    <enterprise-gl-mapping :enterprise-id="enterprise.id" ref="gl_mapping"></enterprise-gl-mapping>
                </div>

                <div class="tab-pane fade show active" id="enterprise-options" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.options.active">
                    <enterprise-options-list :enterprise-id="enterprise.id" ref="options_list"></enterprise-options-list>
                </div>

                <div class="tab-pane fade show active" id="enterprise-documents" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.documents.active">
                    <enterprise-documents-list :enterprise-id="enterprise.id" ref="documents_list"></enterprise-documents-list>
                </div>
                <div class="tab-pane fade show active" id="enterprise-third-party" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.third_party.active">
                    <third-party-vendors-tab level="enterprise" :entity-id="enterprise.id" ref="third_party"></third-party-vendors-tab>
                </div>

            </div>
        </div>
    </div>

</div>

</template>

<script>
import EntitiesEdit from "../EntitiesEdit";
import EnterpriseContactsList from "./EnterpriseContactsList";
import EnterpriseGlMapping from "./EnterpriseGlMapping";
import EnterpriseGeneralView from "./EnterpriseGeneralView";
import EnterpriseOptionsList from "./EnterpriseOptionsList";
import EnterpriseDocumentsList from "./EnterpriseDocumentsList";
import ThirdPartyVendorsTab from "./ThirdPartyVendorsTab";

export default {
    name: "EnterprisesEdit",
    components: {
        ThirdPartyVendorsTab,
        EnterpriseDocumentsList,
        EnterpriseOptionsList, EnterpriseGeneralView, EnterpriseContactsList, EnterpriseGlMapping},
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

                contacts: {
                    title: "Contacts",
                    active: false,
                    disabled: true,
                    titleAddOn: "Contacts",
                    hidden: !this.auth.isMaster,
                },

                gl_mapping: {
                    title: "GL Mapping",
                    active: false,
                    disabled: true,
                    titleAddOn: "GL Mapping",
                },

                options: {
                    title: "Options",
                    active: false,
                    disabled: true,
                    titleAddOn: "Options",
                    hidden: !this.auth.isMaster,
                },

                documents: {
                    title: "Documents",
                    active: false,
                    disabled: true,
                    titleAddOn: "Documents",
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

            enterpriseFields: [
                "code",
                "name",
                {
                    name: "active",
                    type: "boolean",
                },
                {
                    name: "iSolvedClient",
                    title: "iSolved Client",
                },
                {
                    name: "concepts",
                    type: "multiselect",
                    list: {
                        url: "/admin/hierarchy/api/list/concepts"
                    }
                }
            ],

            enterprise: {
                id: 0,
                concepts: [],
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
        axios.get("/admin/hierarchy/api/enterprises/" + this.id).then(response => {
// console.log(response.data);
            this.enterprise = response.data;
            this.$refs.entity_form.$forceUpdate();

            if(this.enterprise.id) {
                this.tabs.contacts.disabled = false;
                this.tabs.gl_mapping.disabled = false;
                this.tabs.options.disabled = false;
                this.tabs.documents.disabled = false;
                this.tabs.third_party.disabled = false;
            }

            this._checkHash(document.location.hash, "mounted");
        });
    },

    computed: {
        pageTitle() {
            let title = "";
            if(!this.enterprise.id) {
                title += "Create New Enterprise";
            } else {
                title += "Edit Enterprise #" + this.enterprise.id + " [" + this.enterprise.name + "]";
            }

            if(this.tabs[this.currentTab].titleAddOn) {
                title += " > " + this.tabs[this.currentTab].titleAddOn;
            }

            return title;
        },

    },

    methods: {
        saveItem() {
            console.log(this.enterprise);

            const _originalId = this.enterprise.id;
            let _urlId = _originalId;
            if(0 === _urlId) _urlId = "new";

            axios.post("/admin/hierarchy/api/enterprises/" + _urlId, this.enterprise).then(response => {
                this.enterprise = response.data.entity;
                if(this.enterprise.id) {
                    this.tabs.contacts.disabled = false;
                    this.tabs.gl_mapping.disabled = false;
                    this.tabs.options.disabled = false;
                    this.tabs.documents.disabled = false;
                    this.tabs.third_party.disabled = false;

                    if(0 === _originalId) {
                        history.replaceState(null, null,"/admin/hierarchy/enterprises/" + this.enterprise.id + "#genereal" );
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
                case "#contacts":
                    _checkListTab("contacts", split[1], "contacts_list");
                    break;

                case "#options":
                    _checkListTab("options", split[1], "options_list");
                    break;

                case "#documents":
                    _checkListTab("documents", split[1], "documents_list");
                    break;

                case "#gl_mapping":
                    if(this.currentTab !== "gl_mapping") {
                        this.currentTab = "gl_mapping";
                    }
                    break;

                case "#third_party":
                    if(this.currentTab !== "third_party") {
                        this.currentTab = "third_party";
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
            // this.tabs.forEach((x, k) => x.active = value === k)
        }
    }
}
</script>

<style scoped>

</style>
