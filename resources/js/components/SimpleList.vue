<template>
<div class="simple-list">
<table>
<thead>
    <tr>
        <th v-for="f in fields">{{f.title}}</th>
        <th>&nbsp;</th>
    </tr>
</thead>
<tfoot>
    <tr>
        <td :colspan="fields.length + 1">
            <div v-if="null === editingIndex">
                <a href="#" @click="addItem()">
                    <svg class="bi bi-plus" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M8 3.5a.5.5 0 01.5.5v4a.5.5 0 01-.5.5H4a.5.5 0 010-1h3.5V4a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
                        <path fill-rule="evenodd" d="M7.5 8a.5.5 0 01.5-.5h4a.5.5 0 010 1H8.5V12a.5.5 0 01-1 0V8z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
        </td>
    </tr>
</tfoot>
<tbody>
    <tr v-for="(item, idx) in list" :key="item.id">
        <td v-for="f in fields">
            <div v-if="idx === editingIndex"><input type="text" v-model="item[f.name]" /></div>
            <div v-else>{{ item[f.name] }}</div>
        </td>

        <td>
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
            <div v-else-if="null === editingIndex">
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
        </td>
    </tr>
</tbody>
</table>
<h1 class="loading" v-if="0 === loadedStatus"><img src="/img/loading.gif" /></h1>

</div>
</template>

<script>
export default {
    name: "SimpleList",
    props: ["fields", "listCode"],
    data() {
        return {
            list: [],
            editingIndex: null,
            originalItem: null,

            loadedStatus: 0,

        }
    },

    mounted() {
        axios.get("/admin/list/"+this.listCode+"/all").then(response => {
// console.log(response);
            this.list = response.data;

            this.loadedStatus = 1;

        });
    },

    methods: {

        addItem() {
            const newItem = {id: 'new'};
            this.list.push(newItem);

            this.editingIndex = this.list.length - 1;
        },

        saveItem() {
            const idx = this.editingIndex;

            const item = this.list[idx];

            axios.post("/admin/list/"+this.listCode+"/"+item.id, item).then(response => {
                this.list[idx] = response.data;
                this.editingIndex = null;
                this.originalItem = null;
// console.log(response);
            });
        },

        openEditor(idx) {
            console.log(idx);
            this.editingIndex = idx;

            const originalItem = {};
            for(let key in this.list[idx]) {
                originalItem[key] = this.list[idx][key];
            }

            this.originalItem = originalItem;
// console.log(this.list);
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

        deleteItem(idx) {
            if(confirm("Are you sure?")) {
                const item = this.list[idx];
                axios.delete("/admin/list/"+this.listCode+"/"+item.id).then(response => {
                    this.list.splice(idx, 1);
                });
            }
        }
    }
}
</script>

<style scoped>
.simple-list table {
    width: 100%;
}
.simple-list table tr td input[type="text"] {
    width: 100%;
}
h1.loading {
    text-align: center;
}
</style>
