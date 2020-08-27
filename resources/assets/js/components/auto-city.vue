<template>
    <div class="row p-0 m-0">
        <div class="col-4 p-0 m-0">
            <div class="form-group" :class="error.city">
                <label class="text-capitalize">{{city}}</label>
                <autocomplete ref="autocomplete"
                              :source="cityUrl"
                              input-class="form-control"
                              :initialDisplay="(defaultCity)?defaultCity:''"
                              :initialValue="(defaultCity)?defaultCity:''"
                              results-value="_id"
                              results-display="_id"
                              clear-button-icon=""
                              @selected="selectedCity"
                              placeholder="type to search"
                              :name="city"
                              :id="city"
                ></autocomplete>
            </div>
        </div>
        <div class="col-4">
            <div class="form-group" :class="error.province">
                <label class="text-capitalize">{{province}}</label>
                <input type="text" v-model="modelProvince" id="province" name="province" class="form-control">
            </div>
        </div>
    </div>
</template>
<script>
    import Autocomplete from 'vuejs-auto-complete'
    import axios from 'axios'

    export default {
        props: ['province', 'city','error','defaultCity', 'defaultProvince'],
        data() {
            return {
                modelProvince: null,
                cityUrl: process.env.MIX_APP_URL + '/api/getarea?area='
            };
        },
        mounted() {
            if(this.defaultProvince){
                this.modelProvince = this.defaultProvince
            }
        },
        computed: {},
        methods: {
            selectedCity: function (event) {
                this.modelProvince = event.selectedObject.region;
                this.$emit('city-value',event.value)
            }
        },
        components: {
            Autocomplete,
        }
    }
</script>
