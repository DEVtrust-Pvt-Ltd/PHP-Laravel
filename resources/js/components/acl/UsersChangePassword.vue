<template>
<div class="change-password-wrapper">
    <entity-edit-form ref='entity_form' v-bind:fields="fields" v-bind:item="item"></entity-edit-form>
    <div class="row">
        <div class="col">
            <button class="btn btn-primary" @click="changePassword();">Save</button>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: "UsersChangePassword",
    data() {
        return {
            item: {
                password: "",
                confirm_password: "",
            }
        }
    },

    computed: {
        fields() {
            return this.$parent.passwordFields;
        }
    },

    methods: {
        changePassword() {
            if(!this.item.password) {
                alert("Please enter password");
                return;
            }

            if(this.item.password !== this.item.confirm_password) {
                alert("Password confirmation failed");
                return;
            }

            this.$emit("change-password", this.item);
        },

        clear() {
            this.item.password = "";
            this.item.confirm_password = "";
        }
    }
}
</script>

<style scoped>

</style>
