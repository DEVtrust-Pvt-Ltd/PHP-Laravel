<template>
    <div>
        <multiselect v-model="value" :options="companyList" placeholder="Select" label=name track-by=name @input="getCompanyId" style="width: 335px"></multiselect>
    </div>
</template>

<script>

import Multiselect from 'vue-multiselect';
import { stringify } from 'querystring';

export default {
  name : "MultiSelect",
 components: { Multiselect },
    data () {
      return {
        value: [],
        options: [],
        url: null
      }
    },
    props: {
    locationList: {
      type: Array
     }
    },
    props: {
    companyList: {
      type: Array
    }
  },
  methods: {

              getCompanyId() {
                  if(this.value) {
                      this.$parent.companyId = this.value.id
                      this.getLocationList();
                  }
                  else {
                      this.$parent.companyId = null
                  }
              },

              getLocationList() {
                if(this.value)
                    axios.get('/admin/get_location_list/'+this.value.id).then(res => {
                         this.$parent.locationList = res.data.data;
                    })
                    .catch(error=>{
                        toast.fire({
                                icon: 'error',
                                title: error.data.message
                        })
                    });  
              }
          }
}

</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>