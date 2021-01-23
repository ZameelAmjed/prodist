<template>
    <table class="table table-bordered">
        <tr>
            <th>Product</th>
            <th class="text-center">Requested Units</th>
            <th class="text-center">Received Units</th>
            <th class="text-center">Expiry Date</th>
            <th class="text-center">Unit Price</th>
            <th class="text-center">Discount (%)</th>
            <th class="text-center">Total Price</th>
        </tr>
        <tr v-for="(item, index) in fields">
            <td>{{item.product.name}}<input :name="'purchase_order_item_id['+index+']'" type="hidden" :value="item.id"/></td>
            <td class="text-center">{{item.requested_units}}</td>
            <td><input class="form-control" v-bind:class="{'has-error': errors['received_units.'+index] }" type="number" step="1" min="0" :name="'received_units['+index+']'" v-model.number="receivedUnits[index]"></td>
            <td><input class="form-control" :name="'expiry_date['+index+']'" type="date"></td>
            <td><input class="form-control" v-bind:class="{'has-error': errors['unit_price.'+index] }" type="number" step="0.01" min="0" :name="'unit_price['+index+']'" v-model.number="unitPrice[index]"></td>
            <td><input class="form-control" v-bind:class="{'has-error': errors['discount.'+index] }" type="number" step="0.01" min="0" :name="'discount['+index+']'" v-model.number="discount[index]"></td>
            <td><input disabled class="form-control disabled read-only" v-bind:class="{'has-error': errors['total_price.'+index] }" type="number" step="0.01" min="0" :name="'total_price['+index+']'" v-model.number="totalPrice[index]"></td>
        </tr>
        <tr>
            <td colspan="6" class="bg-white"></td>
            <td class="text-right font-weight-bold">{{money}} {{totalPrice[fields.length]}}</td>
        </tr>
    </table>

</template>
<style scoped>
.has-error{
        border: 1px solid #a94442 !important;
    }
</style>
<script>
    export default {
        props: ['fields','errors','old'],
        data() {
            return {
                unitPrice:[],
                receivedUnits:[],
                discount:[],
                money:process.env.MIX_MONEY,
            }
        },
        mounted() {
            //Set Old Values
            for(const [index,val] in this.old.unit_price){
                this.unitPrice.push(this.old.unit_price[index]);
                this.receivedUnits.push(this.old.received_units[index]);
                this.discount.push(this.old.discount[index]);
            }

            if(!this.old.unit_price){
                for(let [index] in this.fields){
                  this.unitPrice.push(parseFloat(this.fields[index].product.distributor_price));
                }
            }
        },
        computed: {
            totalPrice (){
                let x = [0]
                let tot = 0;
                for(let [index] in this.fields){
                    let discount = (this.discount[index])?this.discount[index]:0;
                    let unitPrice = (this.unitPrice[index])?this.unitPrice[index]:0;
                    let receivedUnits = (this.receivedUnits[index])?this.receivedUnits[index]:0;
                    x[index] = parseFloat(unitPrice * receivedUnits) * parseFloat((100-discount)/100);
                    tot += x[index];
                }
                x[this.fields.length] = parseFloat(tot).toFixed(2);
                return x
               
            }
        },
        methods: {
            
        }
    }
</script>
