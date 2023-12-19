<template>
<div class="services-wrapper">
    <div class="services-group" v-for="group in list">
        <div class="group-title">
            <input type="checkbox" v-model="group.checked" :id="'group_cb_'+group.id" @click="groupClicked(group, $event)"/>
            <label :for="'group_cb_'+group.id">[{{group.code}}] {{group.name}}</label>
        </div>
        <transition name="slide">
        <div class="services-list" v-show="group.checked">
            <div class="service" v-for="s in group.services">
                <div class="service-title">
                    <input type="checkbox" v-model="s.checked" @click="serviceClicked(s, $event)" :id="'service_cb_'+s.id" />
                    <label :for="'service_cb_'+s.id">[{{s.code}}] {{s.name}}</label>
                </div>
            </div>
        </div>
        </transition>
    </div>
    <div class="buttons">
        <button class="btn btn-primary" :disabled="!changed" @click="save">Save Company Services</button>
    </div>
    <h1 class="loading" v-if="0 === loadedStatus"><img src="/img/loading.gif" /></h1>
</div>
</template>

<script>
export default {
    name: "CompanyServices",
    props: ["companyId"],
    data() {
        return {
            list: [],
            selected:[],
            loadedStatus: 0,
            changed: false,
        }
    },

    async mounted() {
        this.list = (await axios.get("/admin/hierarchy/api/list/services")).data;
        this.selected = (await axios.get("/admin/hierarchy/companies/" + this.companyId + "/services")).data;

        this.list.forEach(g => {
            g.services.forEach(s => {
                if(this.selected.includes(s.id)) {
                    s.checked = true;
                    g.checked = true;
                }
            });
        });

        this.loadedStatus = 1;
        // console.log(s);
    },

    methods: {

        async save() {
            this.selected = (await axios.post("/admin/hierarchy/companies/" + this.companyId + "/services", this.selected)).data;
            this.changed = false;
            alert("Saved OK");
        },

        serviceClicked(service, event) {
            this.changed = true;
            if(event.target.checked) {
                this.selected.push(service.id);
            } else {
                this.selected.splice(this.selected.indexOf(service.id), 1);
            }
        },

        groupClicked(group, event) {
            if(event.target.checked) {
                // this.selected.push(service.id);
            } else {
                group.services.forEach(s => {
                    this.changed = true;
                    s.checked = false;
                    this.selected.splice(this.selected.indexOf(s.id), 1);
                });
            }
        }
    }
}
</script>

<style scoped>
.services-wrapper {
    margin: 10px;
}

.services-list {
    margin-left: 20px;
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
