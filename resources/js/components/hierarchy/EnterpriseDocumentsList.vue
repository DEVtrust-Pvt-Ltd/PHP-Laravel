<script>
import EntitiesSubList from "../EntitiesSubList";

export default {
    name: "EnterpriseDocumentsList",
    extends: EntitiesSubList,
    props:["enterpriseId", ],

    data() {

        let categoriesLoadedCbList = [];

        const categoriesListCallback = new Promise((resolve, reject) => {
            categoriesLoadedCbList.push(resolve);
        });

        let subCategoriesLoadedCbList = [];

        const subCategoriesListCallback = new Promise((resolve, reject) => {
            subCategoriesLoadedCbList.push(resolve);
        });

        const mapper = x => {
            return {
                value: x.id,
                title: x.title,
            }
        };


        const catField = {
            name: "category_id",
            title: "Category",
            type: "select",
            list: {
                callback: () => {return categoriesListCallback;},
                mapper,
                onchange: (v) => {
                    this.selectedCategory = v;

// console.log("category has been changed!", v);
// console.log(this.$refs.entity_form.$refs);
                    this.$refs.entity_form.$refs.select_field[1].updateList();
                }
            }
        };

        const subCatField = {
            name: "subcategory_id",
            title: "Sub-category",
            type: "select",
            list: {
                callback: () => {
                    return subCategoriesListCallback;
                },
                mapper,
                editFilter: x => {
                    return !this.selectedCategory || ("" + this.selectedCategory === "" + x.parent_id);
                },
            }
        };

        return {
            entity: "enterprises/" + this.enterpriseId + "/documents",
            fullCategoriesList: [],

            selectedCategory: null,

            categoriesLoadedCbList,
            subCategoriesLoadedCbList,

            fields: [
                catField,
                subCatField,
                "title",
                // {
                //     name: "description",
                //     type: "longtext"
                // },
            ],

            formFields: [
                catField,
                subCatField,
                "title",
                {
                    name: "description",
                    type: "longtext"
                },
                {
                    name: "notice_date",
                    type: "date"
                },
                {
                    name: "reminder_date",
                    type: "date"
                },
                {
                    name: "expiration_date",
                    type: "date"
                },
                {
                    name: "resolved_date",
                    type: "date"
                },

                {
                    name: "files",
                    type: "attachments",
                    entityClass: "enterprise",
                }
            ],
            list: [],

        // $table->longText('note')->nullable();
        // $table->string('keywords', 1000)->nullable();


            url_hash: "#documents",
            add_button_caption: "Add Enterprise Document",
            modal_title: "Edit Document for " + this.$parent.enterprise.name,
        }
    },

    async mounted() {
        this.fullCategoriesList = (await axios.get('/admin/common/api/categories/enterprise_document')).data;

        this.$nextTick(() => {
            this.categoriesLoadedCbList.forEach(resolve => {
                resolve(this.fullCategoriesList.filter(x => {return !x.parent_id;}));
            });

            this.subCategoriesLoadedCbList.forEach(resolve => {
                resolve(this.fullCategoriesList.filter(x => {return x.parent_id;}));
            });
            // if(this.$refs.entity_form) {
            //     this.$refs.entity_form.$refs.select_field[0].updateList();
            // }

        });
    },

    computed: {
    }
}
</script>

<style scoped>

</style>
