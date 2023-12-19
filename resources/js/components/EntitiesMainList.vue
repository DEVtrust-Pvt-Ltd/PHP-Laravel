<template>
<div class="list-wrapper" :class="extraClass">
    <div class="card">
        <div class="card-header">
            {{pageTitle}}
            <span class="float-right">
                <slot name="title-slot"></slot>
                <button class="btn btn-info btn-sm" @click="addItem" v-if="auth.mayCreate" :disabled="!allowCreate">{{addItemTitle}}</button>
            </span>
        </div>

        <div class="card-body">
            <div v-if="'' !== alert" class="alert alert-success" role="alert">
                {{ alert }}
            </div>

            <slot name="before-table"></slot>

            <entities-list-table :extra-class="extraClass" :header="header" :list="renderedList" ref="entities_list" >
                <template v-slot:extra-header><slot name="extra-header"></slot></template>
            </entities-list-table>

            <slot name="after-table"></slot>

            <h1 class="loading" v-if="0 === loadedStatus"><img src="/img/loading.gif" /></h1>

        </div>
    </div>

</div>

</template>

<script>
import EntitiesList from "./EntitiesList";
import EntitiesListTable from "./EntitiesListTable";

export default {
    name: "EntitiesMainList",
    components:{EntitiesListTable},
    extends: EntitiesList,
    props: {
        auth: {
            type: Object,
            default: {
                mayCreate: true,
                mayEdit: true,
                mayDelete: true,
            }
        }
    },
    data() {
        return {
            allowCreate: true,
        }
    },
    mounted() {

        const _list = this.$refs.entities_list;
        _list.$on("click:edit", (item, idx, event) => {
            this.editItem(item, idx, event);
        });
        _list.$on("click:row", (item, idx, event) => {
            this.editItem(item, idx, event);
        });
        _list.$on("click:delete", (item, idx, event) => {
            this.deleteItem(item, idx, event);
        });
    }

}
</script>

<style scoped>

</style>
