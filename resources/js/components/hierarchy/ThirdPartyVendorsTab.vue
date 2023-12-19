<template>
<div class="third-party-wrapper">

    <table class="main-list-table table table-hover">
        <thead class="thead-light">
        <tr>
            <th>Title</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tfoot></tfoot>
        <tbody>
        <tr v-for="(v, idx) in vendors" @click="rowClicked(v, idx, $event)" class="clickable">
            <td>{{ v.api_name }}</td>
            <td class="controls-td">
                <button class="btn btn-primary btn-sm" @click.stop="editItem(v, idx, $event)">Edit</button>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="empty-list" v-if="vendors.length === 0">
        <h6>No API Vendors currently require data for the Enterprise Level</h6>
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
export default {
    name: "ThirdPartyVendorsTab",
    props: ["level", "entityId", ],
    data() {
        return {
            vendors: [],
            editingItem: null,
            modal_title: "",
            editingIndex: null,
            url_hash: "#third_party",
            formFields: null,
        }
    },

    async mounted() {
        this.vendors = (await axios.get("/admin/third_party_vendors/api/" + this.level + "/list")).data;

        $(this.$refs.edit_form_modal).on("hidden.bs.modal", (e) => {
            this.editingIndex = null;
        });

    },

    methods: {
        async editItem(v, idx, e) {
            e.cancelBubble = true;

            this.modal_title = "Edit 3rd Party Vendor Data For " + v.api_name;

            this.formFields = v.fields.map(f => {
                return {
                    name: f.code,
                    title: f.title,
                };
            });

            this.editingItem = (await axios.get("/admin/third_party_vendors/api/" + this.level + "/" + this.entityId + "/data/" + v.api_code)).data;
            this.editingIndex = idx;
        },

        rowClicked(v, idx, e) {
            this.editItem(v, idx, e);
        },

        async saveItem() {
            const v = this.vendors[this.editingIndex];
            await axios.post("/admin/third_party_vendors/api/" + this.level + "/" + this.entityId + "/data/" + v.api_code, this.editingItem);
            this.editingItem = null;
            this.editingIndex = null;
            alert("Saved OK");
        },
    },
    watch: {
        editingIndex(value) {
            if(null === value) {
                $(this.$refs.edit_form_modal).modal('hide');
                this.editingItem = null;
                history.pushState(null, null, this.url_hash);
            } else {
                $(this.$refs.edit_form_modal).modal();
                history.pushState(null, null, this.url_hash + "/" + this.vendors[this.editingIndex].api_code );
            }
        }
    }

}
</script>

<style scoped>

</style>
