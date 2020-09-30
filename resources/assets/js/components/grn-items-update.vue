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
            <td>{{item.product.name}}</td>
            <td>{{item.requested_units}}</td>
            <td><input class="form-control" name="received_units" v-model.number="receivedUnits[index]"></td>
            <td><input class="form-control" name="expiry_date" type="date"></td>
            <td><input class="form-control" name="unit_price" v-model.number="unitPrice[index]"></td>
            <td><input class="form-control" name="discount" v-model.number="discount[index]"></td>
            <td><input class="form-control" name="total_price" v-model.number="totalPrice[index]"  ref="total_price" :data-id="index" ></td>
        </tr>
    </table>

</template>
<style scoped>
.autocomplete.has-error >>> .autocomplete__box{
        border: 1px solid #a94442 !important;
    }
</style>
<script>
    export default {
        props: ['fields','errors'],
        data() {
            return {
                unitPrice:[],
                receivedUnits:[],
                discount:[],
            }
        },
        mounted() {
            console.log(this.fields)
        },
        computed: {
            totalPrice (){
                let x = [0]
                for(let [index] in this.fields){
                    let discount = (this.discount[index])?this.discount[index]:0;
                    x[index] = parseFloat(this.unitPrice[index] * this.receivedUnits[index]) * parseFloat((100-discount)/100);
                }
                console.log(x)
                return x
               
            }
        },
        methods: {
            
        }
    }
</script>
