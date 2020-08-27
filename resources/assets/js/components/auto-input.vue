<style scoped>
    .list-autocomplete {
        position: absolute;
        width: 100%;
        z-index: 1;
    }
</style>
<template>
    <div>
        <input autocomplete="off" :name="myName" v-on:focus="triggerback()" class="form-control" type="text" v-model="keywords" >
        <ul v-if="filteredResults.length > 0" class="list-group list-autocomplete">
            <li v-on:click="clicked(result)" class="list-group-item list-group-item-hover " v-for="result in filteredResults" :key="result.id" v-text="result.name"></li>
        </ul>
    </div>
</template>
<script>
    export default {
        props:['data','name','value'],
        data() {
            return {
                keywords: (this.value) ? this.value : null,
                results: this.data,
                myName: this.name
            };
        },
        mounted() {
            this.results = [];
        },
        computed: {
            filteredResults () {
                return this.keywords ? this.results.filter(row => row.name.search(new RegExp(`${this.keywords}`, 'i')) !== -1) : ''
            }
        },
        methods:{
            clicked(result){
                this.keywords = result.name;
                this.results = [];
            },
            triggerback(){
                this.results = this.data;
            }
        }
    }
</script>
