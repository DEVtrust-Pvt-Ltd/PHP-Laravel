<script>
import EntitiesSubList from "./EntitiesSubList";

export default {
    name: "EmployeeTaxesList",
    extends: EntitiesSubList,
    props:["employeeId", ],

    data() {
        return {
            rootPath: "/admin/",
            rootApiPath: "/admin/api/",


            entity: "employees/" + this.employeeId + "/taxes",

            fields: [
                {
                    name: "tax_type",
                    type: "select",
                    list: {
                        data:[
                            {value: "FED", title: "FED"},
                            {value: "STATE", title: "STATE"},
                            {value: "LOCAL", title: "LOCAL"},
                        ],
                    }
                },

                {
                    name: "FedFilingStatus",
                    // noList: true,
                },

                {
                    name: "FedExemptions",
                    noList: true,
                },

                {
                    name: "FedBlockTax",
                    type: "boolean",
                    noList: true,
                },

                {
                    name: "FedAddlAmount",
                    noList: true,
                },

                {
                    name: "FedMultiJobsSpouseWorks",
                    type: "boolean",
                    noList: true,
                },

                {
                    name: "FedDependentsAmt",
                    noList: true,
                },

                {
                    name: "FedOtherIncomeAmt",
                    noList: true,
                },

                {
                    name: "FedDeductionAmt",
                    noList: true,
                },

                {
                    name: "TaxExemptStatus",
                    type: "boolean",
                    noList: true,
                },
// ******
                {
                    name: "StateFilingStatus",
                    // noList: true,
                },

                {
                    name: "StateExemptions",
                    noList: true,
                },

                {
                    name: "StateBlockTax",
                    type: "boolean",
                    noList: true,
                },

                {
                    name: "StateAddtlAmount",
                    noList: true,
                },

                {
                    name: "StateAddtlPercent",
                    noList: true,
                },

                {
                    name: "StateAddlExemptions",
                    noList: true,
                },

                {
                    name: "StateExemptionAmount",
                    noList: true,
                },

                {
                    name: "AlternateCalc",
                    noList: true,
                },

// *******
                {
                    name: "StateFilingStatus_Nonres",
                    // noList: true,
                },

                {
                    name: "StateExemptions_Nonres",
                    noList: true,
                },

                {
                    name: "StateBlockTax_Nonres",
                    type: "boolean",
                    noList: true,
                },

                {
                    name: "StateAddtlAmount_Nonres",
                    noList: true,
                },

                {
                    name: "StateAddtlPercent_Nonres",
                    noList: true,
                },

                {
                    name: "StateAddlExemptions_Nonres",
                    noList: true,
                },

                {
                    name: "StateExemptionAmount_Nonres",
                    noList: true,
                },

                {
                    name: "AlternateCalc_NONRES",
                    noList: true,
                },

// *******
                {
                    name: "CityFilingStatus",
                    // noList: true,
                },

                {
                    name: "CityExemptions",
                    noList: true,
                },

                {
                    name: "CityBlockTax",
                    type: "boolean",
                    noList: true,
                },

                {
                    name: "CityAddtlAmount",
                    noList: true,
                },

                {
                    name: "CityAddlPercent",
                    noList: true,
                },

                {
                    name: "CityAddlExemptions",
                    noList: true,
                },

// *******
                {
                    name: "CityFilingStatus_NONRES",
                    // noList: true,
                },

                {
                    name: "CityExemptions_NONRES",
                    noList: true,
                },

                {
                    name: "CityBlockTax_NONRES",
                    type: "boolean",
                    noList: true,
                },

                {
                    name: "CityAddtlAmount_NONRES",
                    noList: true,
                },

                {
                    name: "CityAddlPercent_NONRES",
                    noList: true,
                },

                {
                    name: "CityAddlExemptions_NONRES",
                    noList: true,
                },

// *******
                {
                    name: "SOCCode",
                    noList: true,
                },
                {
                    name: "PSDCode",
                    noList: true,
                },

            ],
            list: [],

            url_hash: "#taxes",
            add_button_caption: "Add Tax Row",
            modal_title: "Edit Tax Row for " + this.$parent.employee.first_name + " " + this.$parent.employee.last_name,
        }
    },
}
</script>

<style scoped>

</style>
