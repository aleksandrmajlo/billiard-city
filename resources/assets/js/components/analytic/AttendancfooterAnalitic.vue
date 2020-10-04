<template>
    <div class="col-xs-12">
        <div class="analyticWrap">
            <div class="analyticItem">
                <img src="/img/colendar-s.png" alt="colendar"/>
                <select v-model="month_start">
                    <option v-for="(month,index) in months" :value="index">{{month}}</option>
                </select>
                <select v-model="year_start">
                    <option v-for="year in years" :value="year">{{ year }}</option>
                </select>
            </div>

            <span class="lines"></span>

            <div class="analyticItem">
                <img src="/img/colendar-s.png" alt="colendar"/>
                <select v-model="month_end">
                    <option v-for="(month,index) in months" :value="index">{{month}}</option>
                </select>
                <select v-model="year_end">
                    <option v-for="year in years" :value="year">{{ year }}</option>
                </select>
            </div>
            <a @click.prevent="Search" class="btn btn-primary">{{$t('Choose')}}</a>
        </div>

        <div class="analitic__table user_table">
            <table class="tables">
                <tr>
                    <td>
                        {{$t('periodAnalic')}}
                        <!--            <img src="/img/arrey.png" alt="arrey" />-->
                    </td>
                    <td>{{$t('billiard')}}</td>
                    <td>{{$t('bar')}}</td>
                    <td>
                        {{$t('kolAnalic')}}
                        <!--            <img src="/img/arrey.png" alt="arrey" />-->
                    </td>
                </tr>

                <tr v-for="(result,index) in results" :key="index">
                    <td>{{result.title}}</td>
                    <td>{{result.count_order_billiards}}</td>
                    <td>{{result.count_order_bars}}</td>
                    <td>{{result.count}}</td>
                </tr>

                <tr>
                    <td>{{$t('Cart4')}}:</td>
                    <td>{{all_count_order_billiards}}</td>
                    <td>{{all_count_order_bars}}</td>
                    <td>{{all}}</td>
                </tr>
            </table>
        </div>
    </div>
</template>
<script>
    import {eventBus} from "../../app";
    import MonthName from "../../lang/month";

    export default {
        name: "AttendancfooterAnalitic",
        data: () => ({
            month_start: "",
            year_start: "",
            month_end: "",
            year_end: "",
            months: MonthName[LanguneThisJs],
            results: [],
            all_count_order_billiards: 0,
            all_count_order_bars: 0
        }),
        computed: {
            years() {
                const year = new Date().getFullYear();
                let arr = Array.from(
                    {length: year - 2018},
                    (value, index) => 2019 + index
                );
                return arr.reverse();
            },
            all() {
                let count = 0;
                this.all_count_order_billiards = 0;
                this.all_count_order_bars = 0;
                this.results.forEach((element) => {
                    count += parseInt(element.count);
                    this.all_count_order_billiards += parseInt(element.count_order_billiards);
                    this.all_count_order_bars += parseInt(element.count_order_bars);
                });
                return count;
            },
        },
        created() {

            eventBus.$on("updateAttendancAnalitic", (arrdate) => {
                this.results = arrdate;
            });
            // установка даты
            eventBus.$on("updateAttendancMonthYearAnalitic", (arrdate) => {
                this.month_start = arrdate.month_start;
                this.year_start = arrdate.year_start;
                this.month_end = arrdate.month_end;
                this.year_end = arrdate.year_end;
            });

        },
        methods: {
            Search() {
                if (
                    this.month_start === "" ||
                    this.year_start === "" ||
                    this.month_end === "" ||
                    this.year_end === ""
                ) {
                    this.showShwal("error", this.$t("errorAttenAnalic"));
                    return !1;
                }
                eventBus.$emit("serchAttendancAnalitic", {
                    month_start: this.month_start,
                    year_start: this.year_start,
                    month_end: this.month_end,
                    year_end: this.year_end,
                });
            },
        },
    };
</script>
