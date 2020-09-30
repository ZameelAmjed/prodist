<template>
    <div>
        <div v-for="(val , index) in fields">
            <div class="row m-0 p-0 align-items-end">
                <div class="col-4 m-0 p-0">
                    <div class="form-group">
                        <label>Product Name</label>
                        <autocomplete ref="autocomplete"
                                      v-bind:class="{'has-error': errors['name.'+index] }"
                                      :source="getNewSupplier"
                                      input-class="form-control"
                                      results-value="id"
                                      results-display="name"
                                      :initial-value="oldName[index]"
                                      :initial-display="oldNameStr[index]"
                                      @selected="addProduct(index)"
                                      :name="'name['+index+']'"
                                      :id="'name['+index+']'"
                        ></autocomplete>
                        <span v-if="errors['name.'+index]" class="error text-danger"><small>{{errors['name.'+index][0]}}</small></span>
                    </div>
                </div>
                <div class="col-2 m-0 p-0"><label>Quantity</label>
                    <div class="form-group ml-2" v-bind:class="{'has-error': errors['requested_units.'+index] }">
                        <input type="number" :value="(oldRequestedUnits[index])" @blur="inputRequestedUnits(index,$event)" :name="'requested_units['+index+']'" class="form-control rounded" step="1"  />
                        <span v-if="errors['requested_units.'+index]" class="error text-danger"><small>{{errors['requested_units.'+index][0]}}</small></span>
                    </div>
                </div>
                <div class="col-6 m-0 p-0">
                    <div class="form-group ml-2" v-bind:class="{'has-error': errors['requested_units.'+index] }">
                        <button type="button" class="btn btn-default text-danger" v-if="index == (fields.length-1) && (index!=0)"
                                @click="clearProduct(index)"><i class="fa fa-remove"></i></button>
                        <br v-if="errors['name.'+index]">
                    </div>
                    </div>
            </div>
        </div>
    </div>

</template>
<style scoped>
.autocomplete.has-error >>> .autocomplete__box{
        border: 1px solid #a94442 !important;
    }
</style>
<script>
    import Autocomplete from 'vuejs-auto-complete'
    import axios from 'axios'

    export default {
        props: ['supplier','oldName','oldRequestedUnits','errors'],
        data() {
            return {
                fields: [0],
                oldNameStr:[],
                getProductUrl: process.env.MIX_APP_URL + '/api/getproducts',
            }
        },
        mounted() {
            //Validation on previous request
            console.log(this.errors['requested_units.0'])
           //if errors show

            //If old values are there show errors
            if (this.oldName && this.oldRequestedUnits) {
                this.fields = [];
                let str = '';
                for (let i = 0; i < this.oldName.length; i++) {
                    this.fields.push(i);
                    str += this.oldName[i] + ',';
                }
                const vm = this;

                axios.get(process.env.MIX_APP_URL + '/api/getproducts?id=' + str).then(
                    function (response) {
                        for (let j = 0; j < response.data.length; j++) {
                            if(response.data[j]){
                                vm.oldNameStr.push(response.data[j].name);
                                vm.$refs.autocomplete[j].$el.children[0].children[1].children[0].value = response.data[j].name;
                            }
                        }

                    }
                )
            }
        },
        computed: {
            getNewSupplier: function () {
                this.fields = [0];
                if(typeof this.$refs.autocomplete == 'object'){
                   typeof this.$refs.autocomplete[0].clear();
                }
                return this.getProductUrl + '?supplier=' + this.supplier + '&q='
            }
        },
        methods: {
            addProduct(index) {
                this.fields.push(this.fields.length)
            },
            clearProduct(index) {
                //clear auto complete & fields
                this.$refs.autocomplete.splice(index, 1);
                this.fields.splice(index, 1);
            },
            inputRequestedUnits(index, $event){
                this.oldRequestedUnits[index] = $event.target.value
            }
        },
        components: {
            Autocomplete,
        }
    }
</script>
