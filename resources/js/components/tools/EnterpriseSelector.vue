<template>
<div class="enterprise-selector-wrapper" :class="extraClass">
    <select-field extra-class="form-control form-control-sm" v-model="localValue" :params="selectParams"></select-field>
</div>
</template>

<script>
import SelectField from "../form-fields/SelectField";
export default {
    name: "EnterpriseSelector",
    components: {SelectField},
    props: {
        extraClass: {
            type: String,
            default: ""
        },
        concept: {
            type: String,
            default: ""
        },
        value: String,
    },
    data() {
        return {
            localValue: this.value,
            selectParams: {
                url: '/admin/hierarchy/api/list/enterprises' + ("" !== this.concept ? "?concept=" + this.concept : ""),
            }
        }
    },

    watch: {
        localValue(value) {
            this.$emit('input', value);
            // console.log("x", value);
        }
    }
}
</script>

<style scoped>
.enterprise-selector-wrapper {
    display: inline-block;
}
</style>
