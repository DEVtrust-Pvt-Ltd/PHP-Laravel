<template>
<div class="list-wrapper" :class="extraClass">
    <div class="card">
        <div class="card-header">
            <a href="/admin/acl/users">Users</a> &gt; {{pageTitle}}
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
                <div class="tab-pane fade show active" id="user-general" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.general.active">
                    <entity-edit-form ref='entity_form' v-bind:fields="userFields" v-bind:item="user"></entity-edit-form>
                    <div class="row">
                        <div class="col">
                            <button class="btn btn-primary" @click="saveItem();">Save</button>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade show active" id="user-roles" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.roles.active">
                    <users-roles ref="roles" :user-roles="user.roles"></users-roles>
                </div>

                <div class="tab-pane fade show active" id="user-associations" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.associations.active">
                    <users-associations ref="associations" :user="user"></users-associations>
<!--                    <users-roles ref="roles" :user-roles="user.roles"></users-roles>-->
                </div>

                <div class="tab-pane fade show active" id="user-change-password" role="tabpanel" aria-labelledby="home-tab" v-if="tabs.change_password.active">
                    <users-change-password ref="change_password"></users-change-password>
                </div>
            </div>
        </div>
    </div>

</div>

</template>

<script>
import EntitiesEdit from "../EntitiesEdit";
import UsersChangePassword from "./UsersChangePassword";
import UsersRoles from "./UsersRoles";
import UsersAssociations from "./UsersAssociations";

export default {
    name: "UsersEdit",
    components: {UsersAssociations, UsersRoles, UsersChangePassword},
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

                roles: {
                    title: "Roles",
                    active: false,
                    disabled: true,
                    titleAddOn: "Roles",
                },

                associations: {
                    title: "Associations",
                    active: false,
                    disabled: true,
                    titleAddOn: "Hierarchy Associations",
                },

                change_password: {
                    title: "Change Password",
                    active: false,
                    disabled: true,
                    titleAddOn: "Change Password",
                }
            },

            currentTab: "general",

            mainFields: [
                "email",
                "name",
                // {
                //     name: "active",
                //     type: "boolean",
                // },
                // {
                //     name: "iSolvedClient",
                //     title: "iSolved Client",
                // },
                // {
                //     name: "concepts",
                //     type: "multiselect",
                //     list: {
                //         url: "/admin/hierarchy/api/list/concepts"
                //     }
                // }
            ],

            passwordFields: [
                {
                    name: "password",
                    type: "password",
                },
                {
                    name: "confirm_password",
                    type: "password",
                },
            ],

            user: {
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
        axios.get("/admin/acl/api/users/" + this.id).then(response => {
// console.log(response.data);
            this.user = response.data;
            this.$refs.entity_form.$forceUpdate();

            if(this.user.id) {
                this.tabs.roles.disabled = false;
                this.tabs.change_password.disabled = false;
                this.tabs.associations.disabled = false;
            }

            this._checkHash(document.location.hash, "mounted");

        });
    },

    computed: {
        pageTitle() {
            let title = "";
            if(!this.user.id) {
                title += "Create New User";
            } else {
                title += "Edit User #" + this.user.id + " [" + this.user.name + "]";
            }

            if(this.tabs[this.currentTab].titleAddOn) {
                title += " > " + this.tabs[this.currentTab].titleAddOn;
            }

            return title;
        },

        userFields() {
            let fields = this.mainFields;
            if(!this.user.id) { //new user
                fields = fields.concat(this.passwordFields);
            }
            return fields;
        },
    },

    methods: {
        saveItem() {
// console.log(this.user);

            if(!this.user.name) {
                alert("Please enter user name");
                return;
            }

            if(!this.user.email) {
                alert("Please enter user e-mail address");
                return;
            }

            const _originalId = this.user.id;

            if(0 === _originalId) {
                if(!this.user.password) {
                    alert("Please enter password");
                    return;
                }

                if(this.user.password !== this.user.confirm_password) {
                    alert("Password confirmation failed");
                    return;
                }
            }

            let _urlId = _originalId;
            if(0 === _urlId) _urlId = "new";

            axios.post("/admin/acl/api/users/" + _urlId, this.user).then(response => {
                this.user = response.data.entity;
                if(this.user.id) {
                    this.tabs.roles.disabled = false;
                    this.tabs.change_password.disabled = false;

                    this.tabs.associations.disabled = false;

                    if(0 === _originalId) {
                        history.replaceState(null, null,"/admin/acl/users/" + this.user.id + "#genereal" );
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
                case "#roles":
                    // _checkListTab("contacts", split[1], "contacts_list");
                    if(this.currentTab !== "roles") {
                        this.currentTab = "roles";
                    }
                    break;

                case "#associations":
                    if(this.currentTab !== "associations") {
                        this.currentTab = "associations";
                    }
                    break;

                case "#change_password":
                    if(this.currentTab !== "change_password") {
                        this.currentTab = "change_password";
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

            if(value === "change_password") {
                this.$nextTick(() => {
                    this.$refs.change_password.$on("change-password", async e => {
                        const response = (await axios.post("/admin/acl/users/" + this.user.id + "/change_password", e)).data;
                        alert("Password changed");

                        this.currentTab = "general";
                        history.pushState(null, null,"#genereal" );

                        // this.$refs.change_password.clear();
                        // console.log('change password', e);
                    });
                });
            }

            if(value === "roles") {
                this.$nextTick(() => {
                    this.$refs.roles.$on("save-roles", async roles => {
                        const response = (await axios.post("/admin/acl/users/" + this.user.id + "/save_roles", {roles})).data;
                        this.user.roles = response;
// console.log(response);return;
                        alert("User roles updated");

                        this.currentTab = "general";
                        history.pushState(null, null,"#genereal" );
                    });
                });
            }

            if(value === "associations") {
                this.$nextTick(() => {
                    this.$refs.associations.$on("save-associations", async associations => {
                        const response = (await axios.post("/admin/acl/users/" + this.user.id + "/save_associations", {associations})).data;
                        // this.user.roles = response;
// console.log(response);return;
                        alert("User associations updated");

                        this.currentTab = "general";
                        history.pushState(null, null,"#genereal" );

                    });
                });
            }
            // this.tabs.forEach((x, k) => x.active = value === k)
        }
    }
}
</script>

<style scoped>

</style>
