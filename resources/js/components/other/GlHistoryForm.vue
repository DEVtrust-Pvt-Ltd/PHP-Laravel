<template>
<div>

    <form target="_blank" method="post">
        <input type="hidden" name="_token" :value="csrf_token" />

        <input type="hidden" name="dateFrom" :value="businessDate[0]" />
        <input type="hidden" name="dateTo" :value="businessDate[1]" />
        <input type="hidden" name="concept_id" :value="conceptId" />
        <input type="hidden" name="type" :value="type" />

        <div class="form-group">
            <label for="enterprise">Enterprise</label>
            <select id="enterprise" name="enterprise_id" class="form-control" v-model="enterpriseId">
                <option v-for="e in enterprises" :value="e.id">{{e.code}} - {{e.name}}</option>
            </select>
        </div>

        <div class="form-group">
            <label for="concept">Concept</label>
            <select id="concept" class="form-control" v-model="conceptId" :disabled="conceptsFiltered.length <= 1">
                <option v-for="c in conceptsFiltered" :value="c.id">{{c.code}} - {{c.name}}</option>
            </select>
        </div>

        <div class="form-group">
            <label for="company">Company</label>
            <select id="company" name="company_id" class="form-control" v-model="companyId">
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

        <div class="form-group" v-if="formIsOk && types.length > 0">
            <label for="type">Type</label>
            <select id="type" class="form-control" v-model="type" :disabled="types.length <= 1">
                <option v-for="t in types" :value="t" v-text="t"></option>
            </select>
        </div>


        <div style="margin-top: 20px;" class="action-buttons">
            <div v-if="!formIsOk">
                <h4>Please Select Company And Date Range</h4>
            </div>
            <div v-else-if="types.length === 0">
                <h4>No GL Data Recorded</h4>
            </div>
            <div v-else>
                <button type="submit" class="btn btn-primary" name="export_qbooks" value="1">Export QBooks</button>
            </div>
        </div>
    </form>

</div>
</template>

<script>
const CancelToken = axios.CancelToken;
let cancelCheckParams;


export default {
    name: "GlHistoryForm",
    props: ["enterprises", "concepts", "companies", "csrf_token", ],
    data() {
        return {
            enterpriseId: this.enterprises[0].id,
            enterprise: null,
            businessDate: [null, null, ],
            companyId: '_all_',
            conceptId: null,
            types: [],
            type: null,
        }
    },

    mounted() {

        this.enterprise = this.enterprises.find(y => {
            return y.id === this.enterpriseId;
        });

        if(this.conceptsFiltered.length > 0) {
            this.conceptId = this.conceptsFiltered[0].id;
        } else {
            this.conceptId = null;
        }
    },

    computed: {
        companiesFiltered() {
            return this.companies.filter(x => {
                return parseInt(x.enterprise_id) === parseInt(this.enterpriseId);
            });
        },

        conceptsFiltered() {
            if(!this.enterprise) return [];

            return this.concepts.filter(x => {
                return this.enterprise.conceptIds.includes(x.id);
            });
        },

        formIsOk() {
            return this.companyId && this.businessDate[0] && this.businessDate[1];
        }
    },

    watch: {
        enterpriseId(v) {
            this.enterprise = this.enterprises.find(y => {
                return y.id === v;
            });

            if(this.conceptsFiltered.length > 0) {
                this.conceptId = this.conceptsFiltered[0].id;
            } else {
                this.conceptId = null;
            }

            this.companyId = '_all_';

            this.paramsUpdated();
        },

        companyId(v) {
            this.paramsUpdated();
        },

        businessDate(v) {
            this.paramsUpdated();
        },
    },

    methods: {
        async paramsUpdated() {
            if(cancelCheckParams) {
                cancelCheckParams();
                cancelCheckParams = null;
            }

            if(!this.formIsOk) return;

            const data = (await axios.post('/admin/gl/check_params', {
                enterpriseId: this.enterpriseId,
                companyId: this.companyId,
                conceptId: this.conceptId,
                dateFrom: this.businessDate[0],
                dateTo: this.businessDate[1],
            }, {
                cancelToken: new CancelToken(function executor(c) {
                    // An executor function receives a cancel function as a parameter
                    cancelCheckParams = c;
                }),
            })).data;

            this.types = data.types;
            if(this.types.length > 0) {
                this.type = this.types[0];
            } else {
                this.type = null;
            }

            console.log(data);

        }
    },
}
</script>

<style scoped>
.date-picker-wrapper {
    width: 105%;
}
</style>
