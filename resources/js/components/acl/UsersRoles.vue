<template>
    <div class="user-roles-wrapper">
        <div class="row form-group" v-for="r in list" :class="r.checked ? 'checked' : ''">
            <label :for="'role_' + r.code" class="col-sm-2 col-form-label">{{r.title}}</label>
            <div class="col-sm-10">
                <input class="form-check-input" type="checkbox" :id="'role_' + r.code" v-model="r.checked" />
            </div>
        </div>

        <div class="row">
            <div class="col">
                <button class="btn btn-primary" @click="saveRoles();">Save</button>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: "UsersRoles",
    props: ['userRoles'],
    data() {
        return {
            list: [],
            options_checked: {}
        }
    },

    async mounted() {
        this.list = (await axios.get('/admin/acl/api/list/user_roles')).data.map(x => {
            x.checked = !!this.userRoles.find(y => {
                return y.role === x.code && y.status === "active";
            });
            return x;
        });
    },

    methods: {
        saveRoles() {
            this.$emit("save-roles", this.list);
        }
    }
}
</script>

<style scoped>
.user-roles-wrapper {
    margin: 20px;
}

.user-roles-wrapper .row.checked {
    background-color: lightgreen;
}
</style>
