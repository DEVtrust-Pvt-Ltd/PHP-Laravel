<template>

<div class="list-wrapper" :class="extraClass">
    <div class="card">
        <div class="card-header">
            <a href="/admin/isolved/documents">Employee Documents Uploaded</a> &gt; Documents Uploaded
        </div>


        <enterprice-company ></enterprice-company>  




    </div>

</div>

</template>
<script>
import EnterpriceCompany from "./EnterpriceCompany";
export default {
    name: "DocumentsUploadedList",
    components: {EnterpriceCompany},
    
}
</script>


<style scoped>
h1.loading {
    text-align: center;
}
</style>


    