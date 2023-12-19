<template>
<div>
    <entity-edit-form ref="i9_data" :fields="i9Fields" :item="item"></entity-edit-form>
    <div class="row">
        <div class="col">
            <button class="btn btn-primary" @click="saveI9();">Save</button>
        </div>
    </div>
</div>
</template>

<script>
import EntityEditForm from "./EntityEditForm";
import statesList from "../tools/states";
export default {
    name: "EmployeeI9Edit",
    components: {EntityEditForm},

    props:["employeeId", ],

    data() {
        return {
            item: {},

            i9Fields: [
                {
                    name: "citizenship_id",
                    title: "Citizenship",
                    type: "select",
                    list: {
                        url: "/admin/common/api/citizenships",
                    }
                },
                "alien_reg_number",
                {
                    name: "work_until",
                    type: "date",
                },
                {
                    name: "eligible_to_work_in_us",
                    type: "boolean",
                },
                {
                    name: "disability",
                    type: "boolean",
                },
                "i9_everify_case_number",
                "dl_number",
                {
                    name: "dl_state",
                    type: "select",
                    list: {
                        data: statesList.map(x => {
                            return {
                                value: x.abbreviation,
                                title: x.name,
                            }
                        }),
                        empty: "Select State ... ",
                    }
                },
                {
                    name: "dl_exp",
                    type: "date",
                },

                "dl_type",
                "driver_insurance_company",

                "driver_insurance_number",

                {
                    name: "driver_insurance_expire",
                    type: "date",
                },
            ],
        }
    },

    async mounted() {
        this.item = (await axios.get("/admin/api/employees/" + this.employeeId + "/i9")).data;
    },

    updated() {
        this.$refs.i9_data.$forceUpdate();
    },

    methods: {
        async saveI9() {
            const response = (await axios.post("/admin/api/employees/" + this.employeeId + "/i9", {
                item: this.item,
            })).data;

            this.item = response.entity;
            alert("Saved OK!");
        },
    }
}

</script>


<style scoped>

</style>
