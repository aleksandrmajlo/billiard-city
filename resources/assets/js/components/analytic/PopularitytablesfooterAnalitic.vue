<template>
    <div class="col-xs-12">
        <div class="analyticWrap">
            <div class="analyticItem">
                <img src="/img/colendar-s.png" alt="colendar"/>
                <select v-model="month_start">
                    <option v-for="(month, index) in months" :value="index">
                        {{ month }}
                    </option>
                </select>
                <select v-model="year_start">
                    <option v-for="year in years" :value="year">{{ year }}</option>
                </select>
            </div>
            <span class="lines"></span>
            <div class="analyticItem">
                <img src="/img/colendar-s.png" alt="colendar"/>
                <select v-model="month_end">
                    <option v-for="(month, index) in months" :value="index">
                        {{ month }}
                    </option>
                </select>
                <select v-model="year_end">
                    <option v-for="year in years" :value="year">{{ year }}</option>
                </select>
            </div>
            <div class="analyticItem">
                <select v-model="table">
                   <option value="-1">{{$t('all')}}</option>
                    <option v-for="table in tables" :key="table.id" :value="table.id">{{table.title}}</option>
                </select>
            </div>
            <a @click.prevent="Search" class="btn btn-primary">{{ $t("Choose") }}</a>
        </div>
        <div class="analitic__table user_table staff-table">
            <div class="mb-5" v-for="(result, index) in results" :key="index">
                 <h2>{{result.title}}</h2>
                <table class="tables popularity" >
                    <tr>
                        <td>{{$t('month')}}
                        </td>
                        <td>
                            {{$t('kolAnalic')}}
                        </td>
                        <td>{{$t('income')}}
                        </td>
                    </tr>
                    <tr v-for="amount in result.amount" :key="amount.date">
                        <td>{{months[amount.month]}} {{amount.year}}</td>
                        <td>{{amount.count}}</td>
                        <td>{{amount.sum}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
    import {eventBus} from "../../app";
    import MonthName from "../../lang/month";
    export default {
        name: "PopularitytablesfooterAnalitic",
        data: () => ({
            month_start: "",
            year_start: "",
            month_end: "",
            year_end: "",
            table:'-1',
            tables:tables,
            months: MonthName[LanguneThisJs],
            results: null,
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
        },
        created() {
            eventBus.$on("updateAttendancAnalitic", (results) => {
                this.results = results;
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
                    table:this.table
                });

            }
        }
    }
</script>
