<template>
    <div class="card">
        <div class="card-header" id="headingOne">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" v-text="title"></button>
            </h2>
        </div>

        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
            <div class="card-body">
                <entity-edit-form ref="the_form" v-bind:item="entity" v-bind:fields="fields"></entity-edit-form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "EntityEditFieldsGroup",
    props: {
        title: {
            type: String,
            default: "",
        },
        entity: {
            type: Object,
            default: null,
        },
        fields: {
            type: Array,
            default: null,
        }
    },

    updated() {
        this.$refs.the_form.$forceUpdate();
    }
}
</script>

<style scoped>

</style>
