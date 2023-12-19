<template>
    <div class="info-wrapper">
        <h1>{{item.code}}</h1>
        <h2>{{item.name}}</h2>
    </div>
</template>

<script>
export default {
    name: "CompanyGeneralView",
    props: ['item'],
}
</script>

<style scoped>
    .info-wrapper {
        margin:20px;
    }
</style>
