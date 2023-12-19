<template>
    <div>
        <entities-list-table id="company_notes" :header="header" :list="renderedList" ref="entities_list"></entities-list-table>
        <h1 class="loading" v-if="0 === loadedStatus"><img src="/img/loading.gif" /></h1>
        <div>
            <button class="btn btn-secondary" @click="addItem">{{ add_button_caption }}</button>
        </div>

        <div id="noteEditFormModal" ref="edit_form_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{modal_title}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-if="editingItem">
                        <entity-edit-form ref='entity_form' v-bind:fields="formFields" v-bind:item="editingItem"></entity-edit-form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="saveItem()">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import EntitiesList from "./EntitiesList";
import EntitiesListTable from "./EntitiesListTable";

export default {
    name: "EntitiesSubList",
    extends: EntitiesList,
    components: {EntitiesListTable},

    data() {
        return {
            editingIndex: null,
            formFields:null
        }
    },

    mounted() {
        if(null === this.formFields) {
            this.formFields = this.fields;
        }
        this.$refs.entities_list.$on("click:edit", (item, idx, event) => {
            this.editingIndex = idx;
        });
        this.$refs.entities_list.$on("click:row", (item, idx, event) => {
            this.editingIndex = idx;
        });
        this.$refs.entities_list.$on("click:delete", (item, idx, event) => {
            if(confirm("Are you sure?")) {
                axios.delete(this.rootApiPath + this.entity + "/" + item.id).then(response => {
                    this.list.splice(idx, 1);
                });
            }
        });


        $(this.$refs.edit_form_modal).on("hidden.bs.modal", (e) => {
            if("new" === this.editingItem.id) {
                this.list.pop();
            }
            this.editingIndex = null;
        });
    },

    computed: {
        editingItem() {
            if(null === this.editingIndex) return false;
            return this.list[this.editingIndex];
        }
    },

    methods: {
        addItem() {
            const _newItem = {id: "new"};
            this.list.push(_newItem);
            this.editingIndex = this.list.length - 1;
        },

        async saveItem() {
            let _urlId = this.editingItem.id;
            if(!_urlId) _urlId = "new";

            const data = (await axios.post(this.rootApiPath + this.entity + "/" + _urlId, this.editingItem)).data;

            const _entity = data.entity;
            this.list.splice(this.editingIndex, 1, _entity);
            // this.renderedList[this.editingIndex] = this.renderItem(_entity, this.editingIndex);
            this.editingIndex = null;
        }
    },

    watch: {
        editingIndex(value) {
            if(null === value) {
                $(this.$refs.edit_form_modal).modal('hide');
                history.pushState(null, null, this.url_hash);
            } else {
                $(this.$refs.edit_form_modal).modal();
                history.pushState(null, null, this.url_hash + "/" + this.list[this.editingIndex].id );
            }
        }
    }


}
</script>

<style scoped>

</style>
