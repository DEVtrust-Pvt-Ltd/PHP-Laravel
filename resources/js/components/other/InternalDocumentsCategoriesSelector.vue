<template>
<div>
    <div class="form-group row">
        <label for="category_id" class="col-sm-2 col-form-label">Category</label>
        <div class="col-sm-10">
            <select id="category_id" name="category_id" class="form-control" required="required" v-model="categoryId">
                <option v-for="c in rootCategories" :value="c.id">{{ c.title }}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="subcategory_id" class="col-sm-2 col-form-label">Subcategory</label>
        <div class="col-sm-10">
            <select id="subcategory_id" name="subcategory_id" class="form-control" required="required" v-model="subCategoryId">
                <option v-for="c in subCategories" :value="c.id">{{ c.title }}</option>
            </select>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: "InternalDocumentsCategoriesSelector",
    props: ["initialCategoryId", "initialSubcategoryId", "categoryType", ],
    data() {
        return {
            categoryId: null,
            subCategoryId: null,
            list: [],
        }
    },

    async mounted() {
        this.list = (await axios.get("/admin/common/api/categories/" + this.categoryType)).data;
        if(this.list.length > 0) {
            if(this.initialCategoryId) {
                this.categoryId = parseInt(this.initialCategoryId);
            } else {
                this.categoryId = this.rootCategories[0].id;
            }

            this.$nextTick(() => {
                if(this.initialSubcategoryId) {
                    this.subCategoryId = parseInt(this.initialSubcategoryId);
                }
            });
        }
    },

    watch: {
        categoryId(v) {
// console.log("here?", v, this.subCategories[0].id);
            if(this.subCategories.length > 0) {
                this.subCategoryId = this.subCategories[0].id;
            }
        },
    },

    computed: {
        rootCategories() {
            return this.list.filter(x => !x.parent_id);
        },

        subCategories() {
            return this.list.filter(x => x.parent_id === this.categoryId);
        }
    }
}
</script>

<style scoped>

</style>
