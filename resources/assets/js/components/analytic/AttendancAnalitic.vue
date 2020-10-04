<template>
    <div class="analytic__attend">
        <div class="charts">
            <bar-chart :chart-data="datacollection"></bar-chart>
        </div>
        <div class="chart__legend">
            <div v-for="(legend,index) in legends" :key="index" class="chart__legend-item">
                <div :style="{ backgroundColor: legend.backgroundColor }" class="legent_item"></div>
                <p>{{legend.title}}</p>
            </div>
        </div>
    </div>
</template>
<script>
    import BarChart from "./BarChart";
    import {eventBus} from "../../app";
    import MonthName from "../../lang/month";
    import colorMonth from "../../colorMonth";

    export default {
        name: "AttendancAnalitic",
        components: {
            BarChart,
        },
        data() {
            return {
                datacollection: {},
                legends: [],
            };
        },
        created() {
            eventBus.$on("AttenDateAnalitic", (date) => {
                 console.log(date)
                this.getDate(date)
            });
            eventBus.$on("serchAttendancAnalitic", (arrdate) => {
                let startDate = new Date(
                    arrdate.year_start,
                    arrdate.month_start
                ).getTime();
                let endDate = new Date(arrdate.year_end, arrdate.month_end, 2).getTime();
                let DayMill = 86400000;
                let res = {};
                while (startDate < endDate) {
                    let d = new Date(startDate);
                    let month = d.getMonth();
                    let year = d.getFullYear();
                    startDate += DayMill;
                    if (typeof res["_" + month + year] == "undefined") {
                        res["_" + month + year] = {
                            month: month,
                            year: year,
                        };
                    }
                }
                let monthData = [];
                for (var key in res) {
                    monthData.push(res[key])
                }
                if (monthData.length) {
                    this.getData(monthData)
                } else {
                    this.showShwal("error", this.$t("errorAttenperiodAnalic"));
                }
            });
        },
        mounted() {
            let monthData = this.ThisMonth();
            this.getData(monthData);
        },
        methods: {
            // при загрузку  три месяца с  этим
            ThisMonth() {
                var d = new Date();
                var month2 = d.setMonth(d.getMonth() - 2);
                var d2 = new Date(month2);

                var d = new Date();
                var month1 = d.setMonth(d.getMonth() - 1);
                var d1 = new Date(month1);

                var d = new Date();
                var month = d.getMonth();

                eventBus.$emit("updateAttendancMonthYearAnalitic", {
                    month_start:d2.getMonth(),
                    year_start:d2.getFullYear(),
                    month_end:d.getMonth(),
                    year_end:d.getFullYear()
                });
                return [
                    {
                        month: d2.getMonth(),
                        year: d2.getFullYear(),
                    },
                    {
                        month: d1.getMonth(),
                        year: d1.getFullYear(),
                    },
                    {
                        month: d.getMonth(),
                        year: d.getFullYear(),
                    },
                ];
            },
            // получение данных по месяцах
            getData(monthData) {
                axios
                    .post("/analytic/attendanceData", {monthData: monthData})
                    .then((response) => {

                        let response_data = response.data;
                        let labels = [];
                        let data = [];
                        let dataTable = [];
                        let backgroundColor = [];
                        this.legends = [];
                        for (let index = 0; index < response_data.length; index++) {
                            const element = response_data[index];
                            labels.push(
                                MonthName[LanguneThisJs][element.month] + " " + element.year
                            );
                            data.push(parseInt(element.count));
                            backgroundColor.push(colorMonth[element.month]);

                            this.legends.push({
                                title:
                                    MonthName[LanguneThisJs][element.month] + " " + element.year,
                                backgroundColor: colorMonth[element.month],
                            });
                            dataTable.push({
                                title: MonthName[LanguneThisJs][element.month] + " " + element.year,
                                count: element.count,
                                count_order_bars: element.count_order_bars,
                                count_order_billiards: element.count_order_billiards,
                            });
                        }
                        this.datacollection = {
                            labels: labels,
                            datasets: [
                                {
                                    label: " ",
                                    backgroundColor: backgroundColor,
                                    data: data,
                                },
                            ],
                        };
                        eventBus.$emit("updateAttendancAnalitic", dataTable);
                    })
                    .catch((error) => {
                    })
                    .finally(function () {
                    });
            },
            // по дням
            getDate(date){
                axios
                    .post("/analytic/attendanceDate", {date: date})
                    .then((response) => {
                        let response_data = response.data;
                        let labels = [];
                        let data = [];
                        let dataTable = [];
                        let backgroundColor = [];
                        this.legends = [];
                        for (let index = 0; index < response_data.length; index++) {
                            const element = response_data[index];
                            let title=element.date+" "+MonthName[LanguneThisJs][element.month] + " " + element.year;
                            labels.push(
                                title
                            );
                            data.push(parseInt(element.count));
                            backgroundColor.push(colorMonth[element.month]);
                            this.legends.push({
                                title:title ,
                                backgroundColor: colorMonth[element.month],
                            });
                            dataTable.push({
                                title: title,
                                count: element.count,
                                count_order_bars: element.count_order_bars,
                                count_order_billiards: element.count_order_billiards,
                            });
                        }
                        this.datacollection = {
                            labels: labels,
                            datasets: [
                                {
                                    label: " ",
                                    backgroundColor: backgroundColor,
                                    data: data,
                                },
                            ],
                        };
                        eventBus.$emit("updateAttendancAnalitic", dataTable);
                    })
            }

        },
    };
</script>