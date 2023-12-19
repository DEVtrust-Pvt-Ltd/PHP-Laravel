<script>
import EntitiesSubList from "./EntitiesSubList";

export default {
    name: "EmployeeDdList",
    extends: EntitiesSubList,
    props:["employeeId", ],

    data() {
        return {
            rootPath: "/admin/",
            rootApiPath: "/admin/api/",


            entity: "employees/" + this.employeeId + "/dd",
            fields: [
                {
                    name: "status",
                    type: "select",
                    list: {
                        data:[
                            {value: "A", title: "Active"},
                            {value: "I", title: "Inactive"},
                        ],
                    }
                },

                {
                    name: "account_type",
                    type: "select",
                    list: {
                        data: [
                            {value: "C", title: "Checking"},
                            {value: "S", title: "Savings"},
                            {value: "R", title: "Paycard Checking"},
                            {value: "P", title: "Paycard Savings"},
                        ],
                    }
                },

                {
                    name: "sequence",
                    type: "list",
                    list: {
                        data: [
                            {value: 0, title: "0 (remaining net)"},
                            {value: 1, title: "1"},
                            {value: 2, title: "2"},
                            {value: 3, title: "3"},
                            {value: 4, title: "4"},
                            {value: 5, title: "5"},
                            {value: 6, title: "6"},
                            {value: 7, title: "7"},
                        ],
                    }
                },

                "amount",
                "percent",

                {
                    name: "routing_number",
                    noList: true,
                },

                {
                    name: "account_number",
                    noList: true,
                },

                {
                    name: "description",
                    noList: true,
                },

            ],
            list: [],

            url_hash: "#dd",
            add_button_caption: "Add Direct Deposit",
            modal_title: "Edit Direct Deposit for " + this.$parent.employee.first_name + " " + this.$parent.employee.last_name,
        }
    },
}
</script>

<style scoped>

</style>
