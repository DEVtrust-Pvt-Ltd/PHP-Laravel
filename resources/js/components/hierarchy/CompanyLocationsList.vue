<script>
import EntitiesSubList from "../EntitiesSubList";
import statesList from "../../tools/states";

export default {
    name: "CompanyLocationsList",
    extends: EntitiesSubList,
    props:["companyId", ],

    data() {
        return {
            entity: "companies/" + this.companyId + "/locations",
            fields: [
                "location",
                "external_id",
                "isolved_code",
                // "min_wage",
                // "tip_wage",
                // "manager_name",
                // "manager_email",
            ],
            formFields: [
                "location",
                "external_id",
                "isolved_code",
                "min_wage",
                "tip_wage",
                "manager_name",
                "manager_email",
                "manager_phone",
                {
                    name: "dm_id",
                    title: "Distinct Manager",
                    type: "select",
                    list: {
                        url: "/admin/hierarchy/api/list/dms/" + this.companyId,
                    }
                },
                {
                    name: "active",
                    type: "boolean",
                },
                "address",
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

                "misc1",
                "misc2",
                "misc3",
                "misc4",
                "misc5",
            ],
            list: [],

            url_hash: "#locations",
            add_button_caption: "Add Location",
            modal_title: "Edit Location for " + this.$parent.company.name,

        }
    },
}
</script>

<style scoped>

</style>
