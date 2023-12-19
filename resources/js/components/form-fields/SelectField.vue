<template>
<select :id="id" v-model="localValue" :class="extraClass" v-on:input="changed($event)" ref="select_el">
    <option v-for="o in options" :value="o.value" >{{ o.title }}</option>
</select>
</template>

<script>

// !!! KEEP in mind, value has to assigned via v-model, rather than :value

// import axios from "axios";
export default {
    name: "SelectField",
    props: {
        id: {
            type: String,
            default: "",
        },
        value: [String, Number, ],
        params: Object,
        extraClass: {
            type: String,
            default: "custom-select",
        }
    },
    data() {
        return {
            localValue: this.value,
            options: []
        }
    },

    mounted() {
        this.updateList();
    },

    updated() {
        this.localValue = this.value;

        const _sel = this.$refs.select_el;
        if(0 < _sel.options.length && _sel.selectedIndex < 0) {
            _sel.selectedIndex = 0;
            const _value = _sel.options[0].value;
            this.localValue = _value;
            this.$emit('input', _value);

            if(this.params.onchange) {
                this.params.onchange(_value);
            }

        }

// console.log("SELECT UPDATED", this.id, this.params);
        // const _value = "" + this.value;

        // for(let i = 0; i < this.$refs.select_el.options.length; i++) {
        //     const _x = "" + this.$refs.select_el.options[i].value;
        //     this.$refs.select_el.options[i].selected = _value.includes(_x);
        // }
    },

    methods: {
        changed(e) {
            const v = e.target.value;
            this.$emit('input', v);

            if(this.params.onchange) {
                this.params.onchange(v);
            }
        },
        updateList() {
            if(this.params.data) {
                let data = this.params.data;
                if(this.params.empty) {
                    data.unshift({value:"", title: this.params.empty, });
                }
                this.options = data;
            } else if(this.params.url) {
                axios.get(this.params.url).then(response => {
                    let data = response.data;
                    if(this.params.empty) {
                        data.unshift({value:"", title: this.params.empty, });
                    }
                    this.options = data;
                });
            } else if(this.params.callback) {
                Promise.resolve(this.params.callback()).then(response => {
                    let data = response;

                    if(this.params.editFilter) {
                        data = data.filter(this.params.editFilter);
                    }

                    if(this.params.mapper) {
                        data = data.map(this.params.mapper);
                    }
                    if(this.params.empty) {
                        data.unshift({value:"", title: this.params.empty, });
                    }
                    this.options = data;
                });
            }

            if(this.params.onchange) {
                this.params.onchange(this.localValue);
            }

        }
    },

    watch: {
        params(x, old) {
// console.log("watch params", this.id, x, old);
            if(x.url && old.url) {
                if(x.url !== old.url) {
                    this.updateList();
                }
            } else if(x.data && old.data) {
                this.updateList();

            } else {
                this.updateList();

            }
            // this.updateList();
        }
    }
}
</script>

<style scoped>

</style>
