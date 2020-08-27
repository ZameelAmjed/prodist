<style scoped>
    .autocomplete__box{
        border-radius: 6px !important;
    }
</style>
<template>
    <div>
        <form v-on:submit.prevent="addtolist()">
        <div class="form-inline">
            <div class="form-group">
                <autocomplete ref="autocomplete"
                              :source="productsUrl+'/api/getproducts?code='"
                              input-class="input-lg form-control"
                              results-value="_id"
                              results-display="product_name"
                              clear-button-icon=""
                              placeholder="type product to search"
                              name="product_name"
                              id="product_name"
                              @selected="setProduct"
                ></autocomplete>
            </div>
            <div class="form-group">
                <input type="number" class="form-control input-lg col-md-4 ml-1" placeholder="Qty" v-model="qty"/>
            </div>
            <button type="submit" class="btn-lg btn btn-primary">Add</button>
        </div>
        </form>
        <div v-if="message" class="card text-white m-0 mb-3" v-bind:class="(message.status) ? 'bg-success' : 'bg-danger'">
            <div class="card-body">
                {{message.message}}
            </div>
        </div>
        <div v-for="(reward, index) in rewardsList" class="card border-success mt-3">
            <div class="card-body p-1">
                <table class="table table-borderless m-0">
                    <tbody>
                    <tr>
                        <td>
                        {{reward.product.model}}
                        </td>
                        <td>
                            {{reward.product.product_name}}
                        </td>
                        <td>
                            {{reward.product.points}} Points X <strong>{{reward.qty}}</strong> Qty
                        </td>
                        <td>
                            <button class="btn btn-link p-0"  v-on:click="removelist(index)"><i class="fa fa-close"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <h4 class="text-right" v-if="rewardsList.length>0">
            Total {{totalPoints}} Points
        </h4>
        <form v-if="rewardsList.length>0" v-on:submit.prevent="submit()">
            <button class="btn btn-success" type="submit" >Submit</button>
        </form>
    </div>
</template>

<script>
    import Autocomplete from 'vuejs-auto-complete'
    import axios from 'axios'

    export default {
        props:['electrician'],
        data(){
            return {
                rewardsList:[],
                productsUrl: process.env.MIX_APP_URL,
                product: null,
                message:null,
            }
        },
        computed : {
            totalPoints() {
                return this.rewardsList.reduce(function (sum, item) {
                    console.log(item)
                    return sum + (item.qty * item.product.points)
                }, 0)
            }
        },
        destroyed () {
            // Remove listener when component is destroyed
            this.$barcodeScanner.destroy()
        },
        methods: {
            setProduct: function (event) {
                this.product = event.selectedObject;
            },
            addtolist(){
                //Add to list
                const prod = this.product;
                for (let i = 0; i < this.rewardsList.length; i++) {
                    if(this.rewardsList[i].product._id == prod._id){
                        return
                    }
                }
                this.rewardsList.push({"product":this.product,"qty":this.qty});
            },
            removelist(index){
                this.rewardsList.splice(index, 1);
            },
            submit(){

                let params = {'id':this.electrician, 'product':this.rewardsList};
                axios
                    .post(process.env.MIX_APP_URL+'/admin/rewards/bulk',params)
                    .then(response => {
                        if(response.data[0].status){
                            this.message = {'status':true, 'message':'Rewards added for points'};
                            this.clear();
                        }
                    },error => {
                        let message = '';
                        error.response.data.forEach(function(item){
                            message = message + item.message;
                        });

                    })
            },
            clear(){
                this.rewardsList = [];
                this.info = null;
            }
        },
        directives: {
            focus: {
                // directive definition
                inserted: function (el) {
                    el.focus()
                }
            }
        },
        components: {
            Autocomplete,
        }
    }
</script>