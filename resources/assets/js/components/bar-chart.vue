<template>
    <CChartBar
            style="height:300px"
            :datasets="[
      {
        data: [10, 22, 34, 46, 58, 70, 46, 23, 45, 78, 34, 12],
        backgroundColor: '#E55353',
        label: 'Sales',
      }
    ]"
            labels="months"
            :options="{ maintainAspectRatio: false }"
    />
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
