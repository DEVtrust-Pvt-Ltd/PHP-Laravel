<template>
<div>
    <entity-edit-fields-group ref="personal_data" title="Personal Data" :fields="personalFields" :entity="item"></entity-edit-fields-group>
    <entity-edit-fields-group ref="organization_data" title="Organization" :fields="organizationFields" :entity="item"></entity-edit-fields-group>
    <entity-edit-fields-group ref="other_data" title="Other" :fields="otherFields" :entity="item"></entity-edit-fields-group>
</div>
</template>

<script>
import EntityEditFieldsGroup from "./EntityEditFieldsGroup";
import statesList from "../tools/states";
export default {
    name: "EmployeeGeneralEdit",
    components: {EntityEditFieldsGroup},
    props: {
        item: {
            type: Object,
            default: null,
        }
    },
    data() {
        return {
            personalFields: [
                {
                    name: "legal",
                    type: "info",
                },
                "employee_number",
                "ssn",
                "prefix_name",
                "first_name",
                "last_name",
                "suffix_name",
                "preferred_name",
                "middle_name",

            ],

            /*
lookup Company table new field Orglevels
if orglevels 3   then location,Dept, Subdept
if Orglevels 4   then company, location,Dept, Subdept
*/


            otherFields: [

                "job",
                "work_location",

                "address1",
                "address2",
                "city",
                {
                    name: "state",
                    type: "select",
                    list: {
                        data: statesList.map(x => {
                            return {
                                value: x.abbreviation,
                                title: x.name,
                            }
                        }),
                    }
                },
                "zip",

                "home_phone",
                "office_phone",
                "mobile_phone",
                "personal_email",

                "self_service_email",
                {
                    name: "self_service_enabled",
                    type: "boolean",
                },

                "time_clock_id",


                {
                    name: "status",
                    type: "select",
                    list: {
                        data: [
                            {value: 'A', title: 'Active', },
                            {value: 'I', title: 'I', },
                            {value: 'T', title: 'Terminated', },
                        ]
                    }
                },

                {
                    name: "status_date",
                    type: "date",
                },

                {
                    name: "hire_date",
                    type: "date",
                },

                {
                    name: "adjusted_service_date",
                    type: "date",
                },

                {
                    name: "include_in_new_hire_report",
                    type: "boolean",
                },

                {
                    name: "birth_date",
                    type: "date",
                },

                {
                    name: "ethnic_origin_id",
                    title: "Ethnic Origin",
                    type: "select",
                    list: {
                        url: "/admin/common/api/ethnic_origins",
                    }
                },

                {
                    name: "eeo_code_id",
                    title: "EEO Code",
                    type: "select",
                    list: {
                        url: "/admin/common/api/eeo_codes",
                    }
                },

                {
                    name: "veteran",
                    type: "boolean",
                },
                {
                    name: "veteran_disabled",
                    type: "boolean",
                },
                {
                    name: "union",
                    type: "boolean",
                },

                {
                    name: "pay_type",
                    type: "select",
                    list: {
                        data: [
                            {value: 'hourly', title: 'Hourly', },
                            {value: 'salary', title: 'Salary', },
                            {value: '1099', title: '1099', },
                        ],
                    }
                },

                "hourly_rate",

                "alternate_rates",

                {
                    name: "gender",
                    type: "select",
                    list: {
                        data: [
                            {value: 'male', title: 'Male', },
                            {value: 'female', title: 'Female', },
                        ],
                    }
                },

                {
                    name: "marital_status",
                    type: "select",
                    list: {
                        data: [
                            {value: 'M', title: 'Married', },
                            {value: 'S', title: 'Single', },
                        ],
                    },
                },

                {
                    name: "work_permit_exp",
                    type: "date",
                },

                "workers_comp",

                {
                    name: "new_hire_sent",
                    type: "boolean",
                },


                {
                    name: "employment_category",
                    type: "select",
                    list: {
                        data: [
                            {value: 'part_time', title: 'Part Time', },
                            {value: 'full_time', title: 'Full Time', },

                        ]
                    }
                },

                {
                    name: "first_work_date",
                    type: "date",
                },

                "to_isolved_datetime", // DateTime

                "source", // Info

                "error_code", // Info
                "error_description", // Info

            ],

            locations: [],
            locationsSelect: [],

            departments: [],
            departmentsSelect: [],

            subDepartments: [],
            subDepartmentsSelect: [],

            companyId: 0,
        }
    },

    mounted() {
        this.companyId = this.$parent.company.id;
    },

    updated() {
        this.companyId = this.$parent.company.id;
        console.log("General Edit Updated", "" + companyId);

        this.$refs.personal_data.$forceUpdate();
        this.$refs.other_data.$forceUpdate();
        this.$refs.organization_data.$forceUpdate();
    },

    computed: {
        organizationFields() {
            const orgLevel = this.$parent.company.org_levels;
            const companyId = this.$parent.company.id;
// console.log("Levels", orgLevel);
// console.log("CompanyId", companyId);

            if(!companyId) return [];

            let fields = [];

            fields.push({
                name: "location_id",
                title: "Location",
                type: "select",
                list: {
                    data: this.locationsSelect,
                }
            });

            fields.push({
                name: "department_id",
                title: "Department",
                type: "select",
                list: {
                    data: this.departmentsSelect,
                }
            });

            fields.push({
                name: "sub_department_id",
                title: "SubDept",
                type: "select",
                list: {
                    data: this.subDepartmentsSelect,
                }
            });


            let idx = 1;
            if(4 === orgLevel) {
                fields.push({
                    name: "org_value" + idx,
                    type: "info",
                });
                ++idx;
            }

            fields.push({
                name: "org_value" + idx,
                type: "info",
            });
            ++idx;

            fields.push({
                name: "org_value" + idx,
                type: "info",
            });
            ++idx;

            fields.push({
                name: "org_value" + idx,
                type: "info",
            });

            return fields;
        }
    },
    watch: {
        async companyId(value, old) {
            if(value) {
                this.locations = (await axios.get("/admin/common/api/locations?companyId=" + value + "&return=full_object")).data;
                this.locationsSelect = this.locations.map(x => {
                    return {
                        value: x.id,
                        title: x.location,
                    }
                });

                this.locHandler(this.item.location_id);

                this.departments = (await axios.get("/admin/common/api/departments?companyId=" + value + "&return=full_object")).data;
                this.departmentsSelect = this.departments.map(x => {
                    return {
                        value: x.id,
                        title: x.title,
                    }
                });

                this.deptHandler(this.item.department_id);

                this.subDepartments = (await axios.get("/admin/common/api/sub_departments?companyId=" + value + "&return=full_object&departmentId=all")).data;
                this.subDepartmentsSelect = [];

                this.renderSubDepartments(this.item.department_id);

                this.sDeptHandler(this.item.sub_department_id);


                this.$refs.organization_data.$forceUpdate();
            }
        },

        "item.location_id": {
            handler(x, old) {
                this.locHandler(x);
            }
        },

        "item.department_id": {
            handler(x, old) {
                this.deptHandler(x);
            }
        },

        "item.sub_department_id": {
            handler(x, old) {
                this.sDeptHandler(x);
            }
        },

    },

    methods: {
        renderSubDepartments(dep_id) {
// console.log("Here??", dep_id, this.subDepartments);
            this.subDepartmentsSelect = this.subDepartments.filter(s => {
                // console.log("H", ("" + s.department_id), ("" + dep_id));
                return ("" + s.department_id) === ("" + dep_id);
            }).map(s => {
                return {
                    value: s.id,
                    title: s.name,
                }
            });
        },

        locHandler(lId) {
            // console.log("lId", lId);
            const l = this.locations.find(loc => {
                return ("" + loc.id) === ("" + lId);
            });

            let org_val;
            if(l) {
                org_val = l.location;
            } else {
                org_val = null;
            }

            const orgLevel = this.$parent.company.org_levels;
            if(4 === orgLevel) {
                this.item.org_value2 = org_val;
            } else {
                this.item.org_value1 = org_val;
            }
        },

        deptHandler(dId) {
// console.log("dId", dId);

            const d = this.departments.find(dep => {
                return ("" + dep.id) === ("" + dId);
            });

            let org_val;
            if(d) {
                org_val = d.name;
            } else {
                org_val = null;
            }

            const orgLevel = this.$parent.company.org_levels;
            if(4 === orgLevel) {
                this.item.org_value3 = org_val;
            } else {
                this.item.org_value2 = org_val;
            }

            if(dId && 0 < this.subDepartments.length) {
                this.renderSubDepartments(dId);
            } else {
                this.subDepartmentsSelect = [];
            }
        },

        sDeptHandler(sId) {
            // console.log("sId", sId);

            const d = this.subDepartments.find(sub => {
                return ("" + sub.id) === ("" + sId);
            });

            let org_val;
            if(d) {
                org_val = d.name;
            } else {
                org_val = null;
            }

            const orgLevel = this.$parent.company.org_levels;
            if(4 === orgLevel) {
                this.item.org_value4 = org_val;
            } else {
                this.item.org_value3 = org_val;
                this.item.org_value4 = null;
            }
        }
    }
}

</script>


<style scoped>

</style>
