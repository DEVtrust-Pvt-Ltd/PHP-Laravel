<template>
    <div class="other-wrapper">
        <div class="mapping-list">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Field</th>
                    <th>Value</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(f, idx) in fields">
                    <td>
                        <!--                    <input v-if="overrideCheck[idx]" type="text" class="form-control form-control-sm" v-model="item.type" />-->
                        <!--                    <div v-else class="read-only-cell" v-text="item.type"></div>-->

                        <div class="read-only-cell" v-text="f.title"></div>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" v-model="f.value" />
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="buttons">
                <div class="float-left">
                    <!--                <button class="btn btn-info btn-sm" @click="addRow()">Add Mapping Row</button>-->
                </div>
                <div class="float-right">
                    <button class="btn btn-primary btn-sm" @click="save()">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CompanyOtherData",
    props: ["enterpriseId", "companyId"],

    data() {
        return {
            fields: [],
        }
    },
    async mounted() {
        this.fields = (await axios.get('/admin/hierarchy/api/companies/' + this.companyId + '/other_fields')).data;
    },

    methods: {
        async save() {
            await axios.post('/admin/hierarchy/api/companies/' + this.companyId + '/other_fields', this.fields);
            alert("Saved OK");
        }
    }
}
</script>

<style scoped>

</style>
