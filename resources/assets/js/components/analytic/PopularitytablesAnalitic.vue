<template>
    <div class="analytic__attend">
        <div class="charts w-100">
            <bar-chart :chart-data="datacollection"></bar-chart>
        </div>
    </div>
</template>
<script>
    import BarChart from "./BarChart";
    import {eventBus} from "../../app";
    import MonthName from "../../lang/month";
    import colorMonth from "../../colorMonth";
    export default {
        name: "PopularitytablesAnalitic",
        components: {
            BarChart,
        },
        data() {
            return {
                datacollection: {},
            };
        },
        created() {
            eventBus.$on("serchAttendancAnalitic", (arrdate) => {
                let startDate = new Date(arrdate.year_start, arrdate.month_start).getTime();
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
                    monthData.push(res[key]);
                }
                if (monthData.length) {
                    this.getMonth(monthData,arrdate.table);
                } else {
                    this.showShwal("error", this.$t("errorAttenperiodAnalic"));
                }
            });

        },
        mounted() {
            let monthData = this.ThisMonth();
            this.getMonth(monthData)
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
                    month_start: d2.getMonth(),
                    year_start: d2.getFullYear(),
                    month_end: d.getMonth(),
                    year_end: d.getFullYear(),
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
            getMonth(monthData,table='-1') {
                axios
                    .post("/analytic/PopularitytablesAjax", {
                        monthData: monthData,
                        table:table
                    }
                        )
                    .then((response) => {
                        eventBus.$emit("updateAttendancAnalitic", response.data);
                        let response_data = response.data;
                        let labels = [];
                        let data = [];
                        // let dataTable = [];
                        let backgroundColor = [];
                        this.legends = [];
                        _.forEach(response_data, (value, key) => {
                            const amounts = value.amount;
                            _.forEach(amounts, (v) => {
                                labels.push(
                                    value.title + " " + MonthName[LanguneThisJs][v.month] + " " + v.year
                                );
                                data.push(parseInt(v.sum));
                                backgroundColor.push(colorMonth[v.month]);
                            })
                        });
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

                    })
                    .catch((error) => {
                    })
                    .finally(function () {
                    });
            },
        }

    }
</script>
