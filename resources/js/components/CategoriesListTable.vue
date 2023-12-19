<template>
    <div class="list-table">
        <div class="list-table-head">
            <div class="row">
                <div class="col" v-for="f in fields">{{f.title}}</div>
                <div class="col">&nbsp;</div>
            </div>
        </div>
        <div class="list-table-body">
            <div class="item-wrapper" v-for="(item, idx) in list" :key="item.id">
                <div class="row">
                    <div class="col" v-for="f in fields">
                        <div v-if="idx === editingIndex"><input type="text" v-model="item[f.name]" /></div>
                        <div v-else>{{ item[f.name] }}</div>
                    </div>

                    <div class="col">
                        <div v-if="idx === editingIndex">
                            <a href="#" title="Cancel" @click="cancelEditing()">
                                <svg class="bi bi-arrow-return-left" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M5.854 5.646a.5.5 0 010 .708L3.207 9l2.647 2.646a.5.5 0 01-.708.708l-3-3a.5.5 0 010-.708l3-3a.5.5 0 01.708 0z" clip-rule="evenodd"/>
                                    <path fill-rule="evenodd" d="M13.5 2.5a.5.5 0 01.5.5v4a2.5 2.5 0 01-2.5 2.5H3a.5.5 0 010-1h8.5A1.5 1.5 0 0013 7V3a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                            <a href="#" title="Save" @click="saveItem()">
                                <svg class="bi bi-box-arrow-in-down" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.646 8.146a.5.5 0 01.708 0L8 10.793l2.646-2.647a.5.5 0 01.708.708l-3 3a.5.5 0 01-.708 0l-3-3a.5.5 0 010-.708z" clip-rule="evenodd"/>
                                    <path fill-rule="evenodd" d="M8 1a.5.5 0 01.5.5v9a.5.5 0 01-1 0v-9A.5.5 0 018 1z" clip-rule="evenodd"/>
                                    <path fill-rule="evenodd" d="M1.5 13.5A1.5 1.5 0 003 15h10a1.5 1.5 0 001.5-1.5v-8A1.5 1.5 0 0013 4h-1.5a.5.5 0 000 1H13a.5.5 0 01.5.5v8a.5.5 0 01-.5.5H3a.5.5 0 01-.5-.5v-8A.5.5 0 013 5h1.5a.5.5 0 000-1H3a1.5 1.5 0 00-1.5 1.5v8z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                        </div>
                        <div v-else-if="null === editingIndex && null === theRoot.editingArea">
                            <a href="#" title="Edit" @click="openEditor(idx)">
                                <svg class="bi bi-pencil" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M11.293 1.293a1 1 0 011.414 0l2 2a1 1 0 010 1.414l-9 9a1 1 0 01-.39.242l-3 1a1 1 0 01-1.266-1.265l1-3a1 1 0 01.242-.391l9-9zM12 2l2 2-9 9-3 1 1-3 9-9z" clip-rule="evenodd"/>
                                    <path fill-rule="evenodd" d="M12.146 6.354l-2.5-2.5.708-.708 2.5 2.5-.707.708zM3 10v.5a.5.5 0 00.5.5H4v.5a.5.5 0 00.5.5H5v.5a.5.5 0 00.5.5H6v-1.5a.5.5 0 00-.5-.5H5v-.5a.5.5 0 00-.5-.5H3z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                            <a href="#" title="Delete" @click="deleteItem(idx)">
                                <svg class="bi bi-x" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M11.854 4.146a.5.5 0 010 .708l-7 7a.5.5 0 01-.708-.708l7-7a.5.5 0 01.708 0z" clip-rule="evenodd"/>
                                    <path fill-rule="evenodd" d="M4.146 4.146a.5.5 0 000 .708l7 7a.5.5 0 00.708-.708l-7-7a.5.5 0 00-.708 0z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="currentLevel < levels && 'new' !== item.id">
                    <div class="col-2 text-center">|___</div>
                    <div class="col-10">
                        <categories-list-table
                            :current-level="1 + currentLevel"
                            :levels="levels"
                            :fields="fields"
                            :full-list="fullList"
                            :level-list="childList(item.id)"
                            :list-code="listCode"
                            :parent-id="item.id"
                            :the-root="theRoot"
                        ></categories-list-table>
                    </div>
                </div>
            </div>
        </div>

        <div class="list-table-foot">
            <div class="row">
                <div class="col-12">
                    <div v-if="null === editingIndex && null === theRoot.editingArea">
                        <a href="#" @click="addItem()">
                            <svg class="bi bi-plus" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M8 3.5a.5.5 0 01.5.5v4a.5.5 0 01-.5.5H4a.5.5 0 010-1h3.5V4a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
                                <path fill-rule="evenodd" d="M7.5 8a.5.5 0 01.5-.5h4a.5.5 0 010 1H8.5V12a.5.5 0 01-1 0V8z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    name: "CategoriesListTable",
    props: ["fields", "listCode", "fullList", "levelList", "currentLevel", "levels", "parentId", "theRoot", ],
    data() {
        return {
            editingIndex: null,
            originalItem: null,
            list: [],
        }
    },
    mounted() {
        this.list = this.levelList;
    },

    watch: {
        editingIndex(value, oldValue) {
            if(value === null) {
                this.theRoot.editingArea = null;
            } else {
                this.theRoot.editingArea = this;
            }
        }
    },

    methods: {
        childList(id) {
            return this.fullList.filter(x => x.parent_id === id);
        },

        addItem() {
            const newItem = {id: 'new', parent_id: this.parentId, };
            this.list.push(newItem);

            this.editingIndex = this.list.length - 1;
        },

        async saveItem() {
            const idx = this.editingIndex;
            const item = this.list[idx];

            this.list[idx] = (await axios.post("/admin/list/"+this.listCode+"/"+item.id, item)).data;
            this.editingIndex = null;
            this.originalItem = null;
        },

        openEditor(idx) {
            // console.log(idx);
            this.editingIndex = idx;

            const originalItem = {};
            for(let key in this.list[idx]) {
                originalItem[key] = this.list[idx][key];
            }

            this.originalItem = originalItem;
        },

        cancelEditing() {
            const idx = this.editingIndex;
            const id = this.list[idx].id;

            if('new' === id) {
                this.list.pop();
                this.editingIndex = null;
                this.originalItem = null;
            } else {
                for(let key in this.originalItem) {
                    this.list[idx][key] = this.originalItem[key];
                }

                this.editingIndex = null;
                this.originalItem = null;
            }
        },

        async deleteItem(idx) {
            if(confirm("Are you sure?")) {
                const item = this.list[idx];
                const response = (await axios.delete("/admin/list/"+this.listCode+"/"+item.id)).data;
                this.list.splice(idx, 1);
            }
        }

    }
}
</script>

<style scoped>
    .simple-list table {
        width: 100%;
    }

    .simple-list .item-wrapper .row .col input[type="text"] {
        width: 100%;
    }

    .simple-list .list-table-head > .row > .col {
        background-color: #666666;
        color: white;
        font-weight: bold;
        margin-right: 3px;
    }

</style>
