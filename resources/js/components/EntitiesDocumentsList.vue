<script>
import ListsManager from "../tools/ListsManager";

export default {
    name: "EntitiesDocumentsList",
    props: {
        extraClass: {
            type: String,
            default: '',
        },
        alert: {
            type: String,
            default: '',
        }
    },

    data() {
        return {

            rootPath: "/admin/isolved/",
            rootApiPath: "/admin/isolved/api/",

            pageTitle: "Dummy Title",
            addItemTitle: "Add New",
            entity: null,

            queryParams: {},

            list: [],
            fields: [],

            renderedList: [],

            allowLoading: true,
            loadedStatus: 1,
        }
    },

    mounted() {
        this.loadData();
        
    },

    methods: {

        async loadData() {
            if(!this.allowLoading) return;
            this.loadedStatus = 0;

             let url = this.rootApiPath + this.entity;

            let params = [];
            for(let paramName in this.queryParams) {
                params.push(paramName + "=" + this.queryParams[paramName]);
            }
            
            if(0 < params.length) {
                url += "?" + params.join("&");
            }

             const loadedData = (await axios.get(url)).data;
            this.list = loadedData;
            this.renderList();

            this.$nextTick(() => {
                this.loadedStatus = 1;
                this.$emit("data:loaded", loadedData);
            });

            return loadedData;
        },

        rowClicked(item, idx, event) {
            this.editItem(item, event);
        },

        editItem(item, idx, event) {
            event.cancelBubble = true;
            document.location.href = this.rootPath + this.entity + "/" + item.id;
        },

        async deleteItem(item, idx, event) {
            event.cancelBubble = true;

            if(confirm("Are you sure?")) {
                const data = (await axios.delete(this.rootApiPath + this.entity + "/" + item.id)).data;
                this.list.splice(idx, 1);
            }
console.log(item);
        },


        addItem() {
            document.location.href = this.rootPath + this.entity + "/new";
        },

        adddocument() {
            document.location.href = this.rootPath + "documentupload";
        },


        renderItem(item, itemIdx) {
            const row = this._fieldsNormalized.filter(f => !f.noList).map((field,fieldIdx) => {
                if(field.type === "select") {
console.log("Select", field);

                    ListsManager.fetchField(field, field.data(item[field.name])).then(data => {
console.log("Fetched", data);
                        this.renderedList[itemIdx].row[fieldIdx].value = data.value;
                    }).catch(err => {
                        console.warn(err);
                        this.renderedList[itemIdx].row[fieldIdx].value = "ERR";
                    });
                }

                if(field.type === "custom") {
                    return {
                        class: field.type + " " + field.name,
                        value: field.data(item),
                    }
                }

                return {
                    class: field.type + " " + field.name,
                    value: field.data(item[field.name]),
                }
            });
            const output = {item, row};
            // if(this.noEdit(item)) {
            //     output.noEdit = true;
            // }
           
            return output;
        },

        renderList() {
            this.renderedList = this.list.map((item, itemIdx) => {
                return this.renderItem(item, itemIdx);
            });
        },

        // noEdit(item) {
        //     return false;
        // },
    },

    computed: {
        header() {
            return this._fieldsNormalized.filter(f => !f.noList).map(f => {
                return {
                    title: f.title,
                    class: f.name,
                }
            });
        },

/*
 * Deprecated, as long is "renderedList" is used
        listView() {
            return this.list.map(item => {
                return {
                    item,
                    row: this._fieldsNormalized.map(f => {
                        return {
                            class: f.type + " " + f.name,
                            value: f.data(item[f.name]),
                        };
                    })
                }
            });
        },
*/

        _fieldsNormalized() {
            return this.fields.map(f => {
                const _f = {};
                if("string" === typeof(f)) {
                    _f.type = "text";
                    _f.name = f;
                    _f.noList = false;
                } else {
                    _f.name = f.name;
                    if(f.title) _f.title = f.title;
                    if(f.type) _f.type = f.type;
                    _f.noList = !!f.noList;
                }

                if(!_f.title) {
                    const lower = _f.name.replace("_", " ").toLowerCase();
                    _f.title = lower.split(" ").map(x => x.charAt(0).toUpperCase() + x.substring(1)).join(" ");
                }
                switch(_f.type) {

                    case "attachments":
                        _f.entityClass = f.entityClass;
                        break;

                    case "custom":

                        if(f.custom) {
                            _f.data = input => {
                                return f.custom(input);
                            };
                        } else if(f.html) {
                            return f.html;
                        }

                        break;

                    case "boolean":
                        let displayAs = {true: 'Y', false: 'Completed'};
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

                    case "select":
                        _f.source = f.list;
                        _f.data = input => {
                            return input;
                        };
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

    },

    watch: {
        list() {
            console.log("EntitiesList Documents list watched!");
            this.renderList();
        }
    }

}
</script>

<style lang="scss">
h1.loading {
    text-align: center;
}

</style>
