<template>
<div class="attachments-wrapper">
    <div class="attachments-list">
        <div class="single-attachment" v-for="a in list">
            <a target="_blank" :href="baseUrl + '/' + a.id + '/download'" v-text="a.filename"></a>
            <button class="btn btn-sm btn-danger" @click="deleteItem(a)">Delete</button>
        </div>
        <div class="empty-list" v-if="list.length === 0">
            <i>The list is empty</i>
        </div>
    </div>
    <div class="add-wrapper" v-if="isAdding">
        <input type="file" ref="add_element" @change="filesChanged($event)"/>
        <button class="btn btn-sm btn-dark" @click="upload" :disabled="0 === this.filesToAdd.length">Upload</button>
    </div>
    <div class="bottom-controls">
        <button class="btn btn-sm btn-primary" @click="addClicked" v-if="!isAdding">Add</button>
        <button class="btn btn-sm btn-primary" @click="cancelAddingClicked" v-else>Cancel Adding</button>
    </div>
</div>
</template>

<script>
export default {
    name: "Attachments",
    props: ['entityClass', 'entityId', ],
    data() {
        return {
            list: [],
            filesToAdd: [],
            isAdding: false,
            baseUrl: "/admin/attachments/api/" + this.entityClass + "/" + this.entityId,
        };
    },

    async mounted() {
        this.list = (await axios.get(this.baseUrl)).data;
    },

    methods: {
        addClicked() {
            this.isAdding = true;
        },

        cancelAddingClicked() {
            this.isAdding = false;
            this.filesToAdd = [];
        },

        filesChanged(e) {
            this.filesToAdd = e.target.files;
            console.log(e.target.files);
        },

        async upload() {
            const formData = new FormData();

            if (!this.filesToAdd.length) return;

            // append the files to FormData
            Array
                .from(Array(this.filesToAdd.length).keys())
                .map(x => {
                    formData.append("file", this.filesToAdd[x], this.filesToAdd[x].name);
                });


            const r = (await axios.post(this.baseUrl + "/upload", formData)).data;
            if(r.ok) {
                this.list.push(r.entity);
            } else {
                alert("ERROR : " + r.error);
            }

            this.cancelAddingClicked();
            // console.log(r);
        },

        async deleteItem(a) {
            if(confirm("Are you sure?")) {
                const r = (await axios.delete(this.baseUrl + "/" + a.id)).data;

                if(r.ok) {
                    this.list.splice(this.list.indexOf(a), 1);
                } else {
                    alert("ERROR : " + r.error);
                }
            }
        },
    }
}
</script>

<style scoped>
    .attachments-list {
        border: dotted 1px #999999;
    }
    .empty-list {
        padding: 10px;
    }

    .add-wrapper {
        margin-top: 8px;
    }

    .bottom-controls {
        margin-top: 8px;
    }
</style>
