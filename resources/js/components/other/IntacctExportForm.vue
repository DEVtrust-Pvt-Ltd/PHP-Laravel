<template>
    <form target="_self" method="post">
        <input type="hidden" name="_token" :value="csrf" />
        <input type="hidden" name="business_date" v-model="businessDate[0]" />
        <input type="hidden" name="business_date_to" v-model="businessDate[1]" />

        <div class="form-group">
            <label for="company">Company</label>
            <select class="form-control" id="company" name="company" required="required" v-model="companyId">
                <option value="">{{visual.emptyCompanyText}}</option>
                <option v-for="c in companiesList" :value="c.id">[{{c.code}}] {{c.name}}</option>
            </select>
        </div>

        <div class="form-group">
            <label for="type">Type</label>
            <select class="form-control" name="type" id="type" v-model="type">
                <option v-if="visual.typesLoadingStatus" value="">Loading ...</option>
                <option v-for="t in typesList" :value="t">{{ t }}</option>
            </select>
        </div>

        <div class="form-group">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="not_exported_only" name="not_exported_only" v-model="notExportedOnly" />
                <label class="form-check-label" for="not_exported_only">
                    Not Exported Only
                </label>
            </div>
        </div>

        <div class="form-group">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="last_60_days_only" name="last_60_days_only" v-model="last60DaysOnly" />
                <label class="form-check-label" for="last_60_days_only">
                    Last 60 Days Only
                </label>
            </div>
        </div>

        <transition name="slide">
        <div class="form-group" v-show="!last60DaysOnly">
            <label for="business_date">Business Date</label>
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
        </transition>

        <div style="margin-top: 20px;">
            <div class="buttons-wrapper" v-if="visual.buttonsLoading">
                <button type="button" disabled="disabled" class="btn btn-secondary no-pointer">Please wait ...</button>
            </div>
            <div class="buttons-wrapper" v-else-if="showButtons">
                <button class="btn btn-primary">Export</button>
                <button class="btn btn-primary" name="post_to_api" value="1">Post to API</button>
                <button class="btn btn-primary" name="post_to_api_2" value="1">Post to API 2</button>
                <button class="btn btn-primary" name="post_to_api_live" value="1">Post to API [Live]</button>
                <button class="btn btn-secondary" name="check_data_existence" value="1" v-if="buttons.missingData">Missing Days and Locations</button>
                <button class="btn btn-secondary" name="check_exported_data" value="1" v-if="buttons.exportedData">Exported Days and Locations</button>
                <button class="btn btn-secondary" name="set_exported_flag" value="1" v-if="buttons.setExport">Set Exported Flag</button>
                <button class="btn btn-secondary" name="undo_exported_flag" value="1" v-if="buttons.undoExport">Undo Exported Flag</button>
            </div>
            <div class="buttons-wrapper" v-else>
                <button type="button" disabled="disabled" class="btn btn-secondary no-pointer">Please choose params</button>
            </div>
        </div>
    </form>

</template>

<script>
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';

export default {
    name: "IntacctExportForm",
    data() {
        return {
            companiesList: [],
            typesList: [],

            companyId: "",
            type: "DSR",

            notExportedOnly: true,
            last60DaysOnly: true,

            businessDate: [null, null],

            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            visual: {
                emptyCompanyText: "Please select ... ",
                typesLoadingStatus: true,
                buttonsLoading: false,
            },

            cache: {
                types: {
                    empty: ['Select company ...', ],
                },
            },

            dataStatus: {},
        }
    },

    async mounted() {
        this.visual.emptyCompanyText = "Loading ... ";

        this.updateTypes();

        this.companiesList = (await axios.get('/admin/intacct/api/companies')).data;
        this.visual.emptyCompanyText = "Please select ... ";

        // this.$refs.date_range.$on('input', data => {
// console.log(data);
//         });
    },

    methods: {
        async updateTypes() {
            this.visual.typesLoadingStatus = true;
            this.type = '';

            let cacheHash = this.companyId ? this.companyId : "empty";
            if(!this.cache.types[cacheHash]) {
                this.cache.types[cacheHash] = (await axios.get("/admin/intacct/api/types/" + this.companyId)).data;
            }
            this.typesList = this.cache.types[cacheHash];
            this.visual.typesLoadingStatus = false;
            this.type = this.typesList[0];

            this.checkData();
        },

        async checkData() {

            this.buttonsLoading = true;

            if(!this.companyId || !this.type) {
                return this.handleMissingData();
            }

            if(!this.last60DaysOnly && !(this.businessDate[0] && this.businessDate[1])) {
                return this.handleMissingData();
            }

            this.dataStatus = (await axios.post("/admin/intacct/api/check_data", {
                companyId: this.companyId,
                type: this.type,
                notExportedOnly: this.notExportedOnly,
                last60DaysOnly: this.last60DaysOnly,
                businessDate: this.businessDate,
            })).data;

console.log(this.dataStatus);
            this.buttonsLoading = false;
        },

        handleMissingData() {
            this.buttonsLoading = false;
        }

    },

    watch: {
        companyId(newValue, oldValue) {
            this.updateTypes();
        },

        type(newValue, oldValue) {
            this.checkData();
        },

        notExportedOnly(newValue, oldValue) {
            this.checkData();
        },

        last60DaysOnly(newValue, oldValue) {
            this.checkData();
        },

        businessDate(newValue, oldValue) {
            console.log('Date Range', newValue, oldValue);
            this.checkData();
        },

    },

    computed: {
        showButtons() {
            return this.companyId && this.type && (this.last60DaysOnly || (this.businessDate[0] && (this.businessDate[1])));
        },

        buttons() {
            return {
                undoExport: this.dataStatus.hasExported,
                setExport: this.dataStatus.hasNonExported,
                exportedData: this.dataStatus.hasExported,
                missingData: this.dataStatus.hasMissing,
            }
        }
    }
}
</script>

<style scoped>
    .slide-enter-active {
        -moz-transition-duration: 0.3s;
        -webkit-transition-duration: 0.3s;
        -o-transition-duration: 0.3s;
        transition-duration: 0.3s;
        -moz-transition-timing-function: ease-in;
        -webkit-transition-timing-function: ease-in;
        -o-transition-timing-function: ease-in;
        transition-timing-function: ease-in;
    }

    .slide-leave-active {
        -moz-transition-duration: 0.3s;
        -webkit-transition-duration: 0.3s;
        -o-transition-duration: 0.3s;
        transition-duration: 0.3s;
        -moz-transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
        -webkit-transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
        -o-transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
        transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
    }

    .slide-enter-to, .slide-leave {
        max-height: 100px;
        overflow: hidden;
    }

    .slide-enter, .slide-leave-to {
        overflow: hidden;
        max-height: 0;
    }

    .date-picker-wrapper.mx-datepicker {
        width:100%;
        display:block;
    }

    button.no-pointer {
        cursor:not-allowed;
    }
</style>
