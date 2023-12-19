<template>
<div class="edit-form-wrapper" :class="extraClass">
<div v-if="localEntity">
    <div v-for="f in formFields">
        <div class="form-group row">
            <label :for="f.name" class="col-sm-2 col-form-label">{{f.title}}</label>
            <div class="col-sm-10">
                <div v-if="f.type === 'boolean'" class="form-check">
                    <input class="form-check-input" type="checkbox" :id="f.name" v-model="localEntity[f.name]" />
                </div>
                <select-field ref="select_field" v-else-if="f.type === 'select'" :id="f.name" v-model="localEntity[f.name]" :params="f.list"></select-field>
                <multi-select ref="multiselect_field" v-else-if="f.type === 'multiselect'" :id="f.name" v-model="localEntity[f.name]" :params="f.list"></multi-select>
                <textarea v-else-if="f.type === 'longtext'" :id="f.name" v-model="localEntity[f.name]"></textarea>
                <input  v-else-if="f.type === 'number'" type="number" class="form-control" :id="f.name" v-model="localEntity[f.name]" />
                <input  v-else-if="f.type === 'password'" type="password" class="form-control" :id="f.name" v-model="localEntity[f.name]" />
                <span v-else-if="f.type === 'info'" class="form-control" :id="f.name" v-text="localEntity[f.name]"></span>
                <span v-else-if="f.type === 'custom'" class="custom-control" :id="f.name" v-html="f.html"></span>
                <date-picker
                    v-else-if="f.type === 'date'"
                    input-class = "form-control"
                    class="date-picker-wrapper"
                    v-model="localEntity[f.name]"
                    format="MM/DD/YYYY"
                    type="date"
                    value-type="format"
                    placeholder="Select date"
                ></date-picker>
                <attachments
                    v-else-if="f.type === 'attachments'"
                    :entity-class="f.entityClass"
                    :entity-id="localEntity.id"
                ></attachments>
                <input  v-else type="text" class="form-control" :id="f.name" v-model="localEntity[f.name]" />
            </div>
        </div>
    </div>
</div>
</div>
</template>

<script>
import MultiSelect from "./form-fields/MultiSelect";
import SelectField from "./form-fields/SelectField";
import Attachments from "./form-fields/Attachments";
export default {
    name: "EntityEditForm",
    components: {Attachments, MultiSelect, SelectField, },
    props: {
        extraClass: {
            type: String,
            default: '',
        },
        fields: {
            type: Array,
            default: null,
        },

        item: {
            type: Object,
            default: null,
        }
    },

    data() {
        return {
            assignedFields: null,
            localEntity: null,
        }
    },

    mounted() {
// console.log("edit-form mounted", this.item);

        if(null !== this.item) {
            this.localEntity = this.item;
        }
    },
    updated() {
// console.log("edit-form updated", this.item.concepts[0], this.$refs.yeah);
        if(this.$refs.multiselect_field)
            this.$refs.multiselect_field.forEach(x => x.$forceUpdate());

        if(this.$refs.select_field)
            this.$refs.select_field.forEach(x => x.$forceUpdate());


        if(null !== this.item) {
            this.localEntity = this.item;
        }
    },

    computed: {
        formFields() {
            return this._fieldsNormalized;
        },

        _fieldsNormalized() {
            let _fields = [];
            if(null !== this.fields) {
                _fields = this.fields;
            } else if(null !== this.assignedFields) {
                _fields = this.assignedFields;
            }

            return _fields.map(f => {
                const _f = {};
                if("string" === typeof(f)) {
                    _f.type = "text";
                    _f.name = f;
                } else {
                    _f.name = f.name;
                    if(f.title) _f.title = f.title;
                    if(f.type) _f.type = f.type;
                }

                if(!_f.title) {
                    const lower = _f.name.replace(/_/g, " ").toLowerCase();
                    _f.title = lower.split(" ").map(x => x.charAt(0).toUpperCase() + x.substring(1)).join(" ");
                }

                switch(_f.type) {
                    case "attachments":
                        _f.entityClass = f.entityClass;
                        break;

                    case "custom":
                        _f.html = '';
                        if(f.html) {
                            _f.html = f.html;
                        } else if(f.custom) {
                            _f.html = f.custom();
                        }
                        break;

                    case "boolean":
                        let displayAs = {true: 'Y', false: 'N'};
                        if("object" === typeof(f) && f.displayAs) {
                            displayAs = f.displayAs;
                        }

                        _f.data = input => {
                            if(1 === input || '1' === input || true === input) {
                                return displayAs.true;
                            } else {
                                return displayAs.false;
                            }
                        };

                        break;

                    case "multiselect":
                    case "select":
                        _f.list = f.list;
                        break;

                    case "text":
                    case "longtext":
                    case "number":
                    default:
                        _f.data = input => {
                            return input;
                        };
                        break;
                }

                return _f;
            });
        }

    }
}
</script>

<style scoped>
textarea {
    width: 100%;
    height: 8em;
}
</style>
