<template>
<div class="associations-wrapper">
    <div class="enterprise-wrapper" v-for="e in enterprises">
        <label :for="'enterprise_' + e.code">
            <input type="checkbox" :id="'enterprise_' + e.code" v-model="e.checked" />
            [{{e.code}}] {{e.name}}
        </label>
        <transition name="slide">
            <div class="companies-list-wrapper" v-show="e.checked">
                <div class="company-wrapper" v-for="c in e.companies">
                    <label :for="'company_' + c.code">
                        <input type="checkbox" :id="'company_' + c.code" v-model="c.checked" />
                        [{{c.code}}] {{c.name}}
                    </label>
                </div>
            </div>
        </transition>
    </div>

    <div class="row">
        <div class="col">
            <button class="btn btn-primary" @click="save();">Save</button>
        </div>
    </div>

</div>
</template>

<script>
export default {
    name: "UsersAssociations",
    props: ['user'],
    data() {
        return {
            enterprises: [],
        }
    },

    async mounted() {
        const userData = (await axios.get('/admin/acl/api/users/' + this.user.id + '/associations')).data;
// console.log(userData);
        this.enterprises = (await axios.get('/admin/acl/api/list/associations')).data.map(e => {
            e.checked = !!userData.enterprises.find(x => {
                return x.enterprise_id === e.id;
            });
            e.companies = e.companies.map(c => {
                c.checked = !!userData.companies.find(x => {
                    return x.company_id === c.id;
                });
                return c;
            });
            return e;
        });
    },

    methods: {
        save() {
            this.$emit("save-associations", this.enterprises);
        }
    }

}
</script>

<style scoped>
.associations-wrapper {
    margin: 20px;
}

.companies-list-wrapper {
    margin-left: 40px;
}

.slide-enter-active {
    -moz-transition-duration: 0.3s;
    -webkit-transition-duration: 0.3s;
    -o-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -moz-transition-timing-function: ease-in;
    -webkit-transition-timing-function: ease-in;
    -o-transition-timing-function: ease-in;
    transition-timing-function: ease-in;
}

.slide-leave-active {
    -moz-transition-duration: 0.3s;
    -webkit-transition-duration: 0.3s;
    -o-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -moz-transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
    -webkit-transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
    -o-transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
    transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
}

.slide-enter-to, .slide-leave {
    max-height: 100px;
    overflow: hidden;
}

.slide-enter, .slide-leave-to {
    overflow: hidden;
    max-height: 0;
}

</style>
