<template>
    <div class="panel panel-default mt-5">
        <div class="panel-title"><h6>Sales Demographics</h6></div>
        <div class="panel-body">
            <div class="row p-0 m-0">
                <div class="col-4 col-md-4 p-0 m-0">
                    <div class="form-group">
                        <label class="text-capitalize" for="dealer_id">dealer</label>
                        <autocomplete
                                :source="dealerUrl"
                                :initialDisplay="(dealer)?(dealer.name):''"
                                :initialValue="(dealer)?(dealer.id):''"
                                placeholder="type to search"
                                input-class="form-control"
                                results-value="id"
                                results-display="business_name"
                                :results-display="formattedDisplay"
                                clear-button-icon=""
                                name="dealer_id"
                                @selected="selectedDealer"
                                id="dealer_id"
                        ></autocomplete>
                    </div>
                </div>
                <div class="col-4 col-md-4 ">
                    <div class="form-group">
                        <label class="text-capitalize" for="region">region</label>
                        <input v-model="dealerRegion" id="region" name="region" class="form-control"/>
                    </div>
                </div>
                <div class="col-4 col-md-4 ">
                    <div class="form-group">
                        <label class="text-capitalize" for="area">area</label>
                        <input v-model="dealerArea" name="area" class="form-control" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import Autocomplete from 'vuejs-auto-complete'
    import axios from 'axios'

    export default {
        props:['dealer'],
        data() {
            return {
                dealerArea: '',
                dealerRegion: '',
                dealerUrl: process.env.MIX_APP_URL + '/api/getdealer?name='
            }
        },
        mounted() {
            this.dealerArea = (this.dealer)?(this.dealer.area):''
            this.dealerRegion = (this.dealer)?(this.dealer.region):''
        },
        methods: {
            setDealer: async function (event) {
                const vm = this;
                await axios.get( process.env.MIX_APP_URL + '/api/getbank?code=true&name='+event.value).then(
                    function (response) {
                        vm.bankCode = response.data[0]._id;
                       // vm.bankCity = vm.$parent.city;
                    }
                )
            },
            formattedDisplay(result){
                return (result.business_name) ? result.business_name: result.name;
            },
            selectedDealer: function(event){
                this.dealerArea = event.selectedObject.area;
                this.dealerRegion = event.selectedObject.region;
            }
        },
        components: {
            Autocomplete,
        }
    }
</script>
