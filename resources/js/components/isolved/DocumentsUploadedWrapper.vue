<template>
    <documents-uploaded-list ></documents-uploaded-list>    
</template>

<script>
import DocumentsUploadedList from "./DocumentsUploadedList";
export default {
    name: "DocumentsUploadedWrapper",
    components: {DocumentsUploadedList},
    props: {
        alert: {
            type: String,
            default: '',
        },
        auth: {
            type: Object,
            default: {
                mayCreate: true,
                mayEdit: true,
                mayDelete: true,
            }
        }
    }   
}
</script>

