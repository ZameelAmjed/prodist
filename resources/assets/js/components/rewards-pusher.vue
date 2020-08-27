<template>
    <div>
        <div class="form-group">
            <form v-on:submit.prevent="addtolist()">
                <input class="input-lg form-control"
                       type="text"
                       placeholder="Scan Your Barcode Here"
                       v-model="barcode"
                       v-focus
                />
            </form>
        </div>
        <div v-if="message" class="card text-white m-0 mb-3" v-bind:class="(message.status) ? 'bg-success' : 'bg-danger'">
            <div class="card-body">
                {{message.message}}
            </div>
        </div>
        <div v-for="(reward, index) in rewardsList" class="card border-success">
            <div class="card-body p-1">
                <table class="table table-borderless m-0">
                    <tbody>
                    <tr>
                        <td>
                            <h5>{{reward.barcode}}</h5>
                        </td>
                        <td>
                            {{reward.product_name}}
                        </td>
                        <td>
                            {{reward.model}}
                        </td>
                        <td>
                            {{reward.points}} points
                        </td>
                        <td>
                            <button class="btn btn-link p-0"  v-on:click="removelist(index)"><i class="fa fa-close"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <form v-if="rewardsList.length>0" v-on:submit.prevent="submit()">
            <button class="btn btn-success" type="submit" >Submit</button>
        </form>
    </div>
</template>

<script>
    export default {
        props:['electrician'],
        data(){
            return {
                barcode:null,
                rewardsList:[],
                info:null,
                message:null
            }
        },
        mounted() {
            console.log('Component mounted.'+this.electrician);
        },
        created () {
            // Add barcode scan listener and pass the callback function
            this.$barcodeScanner.init(this.onBarcodeScanned)
        },
        destroyed () {
            // Remove listener when component is destroyed
            this.$barcodeScanner.destroy()
        },
        methods: {
            // Create callback function to receive barcode when the scanner is already done
            onBarcodeScanned (barcode) {
                console.log(barcode);
                this.barcode = barcode;
                this.addtolist();
                // do something...
            },
            // Reset to the last barcode before hitting enter (whatever anything in the input box)
            resetBarcode () {
                let barcode = this.$barcodeScanner.getPreviousCode()
                // do something...
            },
            addtolist(){
                if(this.isrepeat()){
                    return;
                }
                console.log(this.barcode);
                this.message = null;
                //get axios
                axios
                    .get(process.env.MIX_APP_URL+'/admin/points?barcode='+this.barcode)
                    .then(response => {
                       if(response.data.status){
                           let data = response.data.data;
                           data.barcode = this.barcode;
                           this.rewardsList.push(data);
                           this.barcode = '';
                       }else{
                           this.message = response.data;
                       }
                    },error => {
                    })
            },
            removelist(index){
                this.rewardsList.splice(index, 1);
            },
            isrepeat(){
                let status = false;
                this.rewardsList.forEach(
                    item => {
                        if(item.barcode === this.barcode){
                            console.log('sss');
                            this.message = {'status':false, "message":"Barcode repeated"};
                            status = true;
                        }
                    }
                );
                return status;
            },
            submit(){
                let barcodes = [];
                for(let i=0; i<this.rewardsList.length;i++){
                    barcodes.push(this.rewardsList[i].barcode);
                }
                let params = {'electrician':this.electrician, 'barcodes':barcodes};
                axios
                    .post(process.env.MIX_APP_URL+'/admin/rewards',params)
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
                        this.message = {'status':false, 'message':message};
                    })
            },
            clear(){
                this.barcode = null;
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
        }
    }
</script>
