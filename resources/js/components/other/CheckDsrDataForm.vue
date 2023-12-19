<template>
<div>
    <input type="hidden" name="dateFrom" :value="businessDate[0]" />
    <input type="hidden" name="dateTo" :value="businessDate[1]" />

    <div class="form-group">
        <label for="enterprise">Enterprise</label>
        <select id="enterprise" name="enterprise_id" class="form-control" v-model="enterpriseId">
            <option v-for="e in enterprises" :value="e.id">{{e.code}} - {{e.name}}</option>
        </select>
    </div>

    <div class="form-group">
        <label for="company">Company</label>
        <select id="company" name="company_id" class="form-control">
            <option value="_all_">- - - All Companies - - -</option>
            <option v-for="c in companiesFiltered" :value="c.id">{{c.code}} - {{c.name}}</option>
        </select>
    </div>

    <div class="form-group">
        <label for="dates">Date Range</label>
        <date-picker
            ref="date_range"
            input-class = "form-control"
            class="date-picker-wrapper"
            v-model="businessDate"
            format="MM/DD/YYYY"
            type="date"
            value-type="format"
            range
            placeholder="Select date range"></date-picker>
    </div>
</div>
</template>

<script>
export default {
    name: "CheckDsrDataForm",
    props: ["enterprises", "companies", ],
    data() {
        return {
            enterpriseId: this.enterprises[0].id,
            businessDate: [null, null, ],
        }
    },

    computed: {
        companiesFiltered() {
            return this.companies.filter(x => {
                return parseInt(x.enterprise_id) === parseInt(this.enterpriseId);
            });
        }
    }
}
</script>

<style scoped>
.date-picker-wrapper {
    width: 105%;
}
</style>
