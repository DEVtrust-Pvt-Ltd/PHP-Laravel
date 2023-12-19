<script>
    export default {
        name: "EntitiesEdit",
        props: {
            extraClass: {
                type: String,
                default: '',
            },
            alert: {
                type: String,
                default: '',
            },
            id: String,
        },

    }
</script>

<style lang="scss">
.edit-form-wrapper {
    margin: 20px;
}
</style>
