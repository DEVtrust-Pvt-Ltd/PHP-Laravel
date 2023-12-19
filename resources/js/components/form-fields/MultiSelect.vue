<template>
<select :id="id" multiple class="custom-select" v-on:input="changed($event)" ref="select_el">
<!--    v-bind:value="value"-->
<!--    :selected="value.includes(o.value)"-->
    <option v-for="o in options" :value="o.value" >{{ o.title }}</option>
</select>
</template>

<script>
export default {
    name: "MultiSelect",
    props: ["id", "value", "params", ],
    data() {
        return {
            // localValue: [],
            options: []
        }
    },

    mounted() {

        if(this.params.data) {
            this.options = this.params.data;
        } else if(this.params.url) {
            axios.get(this.params.url).then(response => {
                this.options = response.data;
            });
        }
    },

    updated() {
        const _value = this.value.map(x => "" + x);

        for(let i = 0; i < this.$refs.select_el.options.length; i++) {
            const _x = "" + this.$refs.select_el.options[i].value;
            this.$refs.select_el.options[i].selected = _value.includes(_x);
        }
    },

    methods: {
        changed(e) {
            const _value = [];
            for(let i = 0; i < e.target.selectedOptions.length; i ++) {
                _value.push(e.target.selectedOptions[i].value);
            }
// console.log("input", _value);
// console.log("input", this.options)
;
            this.$emit('input', _value);
        }
    }
}
</script>

<style scoped>

</style>
