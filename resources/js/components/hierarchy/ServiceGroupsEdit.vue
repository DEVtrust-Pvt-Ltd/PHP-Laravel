<template>
<div class="list-wrapper" :class="extraClass">
    <div class="card">
        <div class="card-header">
            <a href="/admin/hierarchy/service_groups">Services</a> &gt; {{pageTitle}}
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
                    <entity-edit-form ref='entity_form' v-bind:fields="entityFields" v-bind:item="entity"></entity-edit-form>
                    <div class="row">
                        <div class="col">
                            <button class="btn btn-primary" @click="saveItem();">Save</button>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade show active" id="services" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.subscriptions.active">
                    <service-groups-services-list :group-id="entity.id" ref="services_list"></service-groups-services-list>
                </div>
            </div>
        </div>
    </div>

</div>

</template>

<script>
import EntitiesEdit from "../EntitiesEdit";
import ServiceGroupsServicesList from "./ServiceGroupsServicesList";

export default {
    name: "ServiceGroupsEdit",
    components: {ServiceGroupsServicesList},
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

                subscriptions: {
                    title: "Subscriptions",
                    active: false,
                    disabled: true,
                    titleAddOn: "Subscriptions",
                },

            },

            currentTab: "general",

            entityFields: [
                "code",
                "name",
            ],

            entity: {
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
        axios.get("/admin/hierarchy/api/service_groups/" + this.id).then(response => {
// console.log(response.data);
            this.entity = response.data;
            this.$refs.entity_form.$forceUpdate();

            if(this.entity.id) {
                this.tabs.subscriptions.disabled = false;
            }

            this._checkHash(document.location.hash, "mounted");
        });
    },

    computed: {
        pageTitle() {
            let title = "";
            if(!this.entity.id) {
                title += "Create New Service";
            } else {
                title += "Edit Service #" + this.entity.id + " [" + this.entity.name + "]";
            }

            if(this.tabs[this.currentTab].titleAddOn) {
                title += " > " + this.tabs[this.currentTab].titleAddOn;
            }

            return title;
        }
    },

    methods: {
        saveItem() {
            console.log(this.entity);

            const _originalId = this.entity.id;
            let _urlId = _originalId;
            if(0 === _urlId) _urlId = "new";

            axios.post("/admin/hierarchy/api/service_groups/" + _urlId, this.entity).then(response => {
                this.entity = response.data.entity;
                if(this.entity.id) {
                    this.tabs.subscriptions.disabled = false;

                    if(0 === _originalId) {
                        history.replaceState(null, null,"/admin/hierarchy/service_groups/" + this.entity.id + "#genereal" );
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
                case "#subscriptions":
                    _checkListTab("subscriptions", split[1], "services_list");
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
