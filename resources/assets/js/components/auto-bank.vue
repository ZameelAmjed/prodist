<template>
    <div class="panel panel-default mt-5">
        <div class="panel-title"><h6>Bank Account Information</h6></div>
        <div class="panel-body">
            <div class="row m-0 p-0">
                <div class="col-4 m-0 p-0">
                    <div class="form-group">
                        <label for="bank_account_no">Bank Account No</label>
                        <input type="text" id="bank_account_no" name="bank_account_no" class="form-control" >
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>Bank Name</label>
                        <autocomplete ref="autocomplete"
                                      :source="getBankUrl"
                                      input-class="form-control"
                                      results-value="bankname"
                                      results-display="bankname"
                                      clear-button-icon=""
                                      @selected="setBank"
                                      name="bank_name"
                                      id="bank_name"
                        ></autocomplete>
                    </div>
                </div>
            </div>
            <div class="row p-0 m-0">
                <div class="col-4 p-0 m-0">
                    <div class="form-group">
                        <label for="bank_city">Bank City</label>
                        <input v-model="$parent.city" type="text" id="bank_city" name="bank_city" class="form-control">
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="bank_code">Bank Code</label>
                        <input v-model="bankCode" type="text" id="bank_code" name="bank_code" class="form-control">
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
        data() {
            return {
                bankCity: this.$parent.city,
                bankCode: '',
                getBankUrl: process.env.MIX_APP_URL + '/api/getbank?name='
            };
        },
        mounted() {

        },
        computed: {
        },
        methods: {
            setBank: async function (event) {
                const vm = this;
                await axios.get( process.env.MIX_APP_URL + '/api/getbank?code=true&name='+event.value).then(
                    function (response) {
                        vm.bankCode = response.data[0]._id;
                       // vm.bankCity = vm.$parent.city;
                    }
                )
            }
        },
        components: {
            Autocomplete,
        }
    }
</script>
