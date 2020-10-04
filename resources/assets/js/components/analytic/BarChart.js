import { Bar, mixins } from 'vue-chartjs'
const { reactiveProp } = mixins
export default {
    name:'AttendanceBarchart',
    mixins: [reactiveProp],
    extends: Bar,
    data: () => ({
        options: {
            scales: {
                ticks: {
                    display: false
                },
                xAxes: [{
                    // display: false
                }],
                yAxes: [{
                    // display: false,
                    ticks: {
                        beginAtZero: true
                    },

                }],
            },
            gridLines: {
                display: false
            },
            legend: {
                display: false
            },
            tooltips: {
                position: 'nearest'
            },
            responsive: true,
            maintainAspectRatio: false,
        }
    }),
    mounted() {
        this.renderChart(this.chartData, this.options)
    },
}