<script>
import EntitiesMainList from "../EntitiesMainList";

export default {
    name: "UsersList",
    extends: EntitiesMainList,

    data() {
        return {
            pageTitle: "Users",
            addItemTitle: "Add User",
            entity: "users",

            rootPath: "/admin/acl/",
            rootApiPath: "/admin/acl/api/",

            fields:[
                "email",
                "name",
                {
                    name: "roles",
                    type: "custom",
                    custom(user) {
                        return user.roles.map(x => x.nice).join(', ');
                    }
                }
                // {
                //     name: "active",
                //     type: "boolean",
                // },
            ]
        }
    },

    methods: {
        noDelete(item) {
            return (1 === 0 + item.id);
        }
    }
}
</script>

<style scoped>

</style>
