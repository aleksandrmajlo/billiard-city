<template>
    <div class="calendars__wrap max">
        <div class="calendars"></div>

        <nav class="calendars-nav">
            <button class="btn btn-calendars calendars-week">{{$t('calednar7')}}</button>
            <button class="btn btn-calendars calendars-month">{{$t('calednar30')}}</button>
            <button class="btn btn-calendars calendars-decade">{{$t('calednar3month')}}</button>
            <!-- <button class="btn btn-calendars calendars-today">{{$t('calednarToday')}}</button> -->
        </nav>
    </div>
</template>

<script>
    import pickmeup from "../../plugins/pickmeup.js";
    import {eventBus} from "../../app";

    export default {
        name: "AttendancCalendar",
        mounted() {
            let self = this;
            pickmeup(".calendars", {
                flat: true,
                mode: "range",
                class_name: "pickmeop",
            });

            /*
            //1 дней
            $(".calendars-today").click(function () {
                let data = {};
                let plus_7_days = new Date();
                data.date_start =
                    plus_7_days.getFullYear() +
                    "-" +
                    (plus_7_days.getMonth() + 1) +
                    "-" +
                    plus_7_days.getDate();
                pickmeup(".calendars").set_date([new Date()]);
                data.date_end = null;

                eventBus.$emit("AttenDateAnalitic", data);
                $(".btn-calendars").removeClass("active");
                $(this).addClass("active");

            });
            */

            // 7 дней
            $(".calendars-week").click(function () {
                let data = {};
                let plus_7_days = new Date();
                data.date_end =
                    plus_7_days.getFullYear() +
                    "-" +
                    (plus_7_days.getMonth() + 1) +
                    "-" +
                    plus_7_days.getDate();

                plus_7_days.setDate(plus_7_days.getDate() - 7);
                data.date_start =
                    plus_7_days.getFullYear() +
                    "-" +
                    (plus_7_days.getMonth() + 1) +
                    "-" +
                    plus_7_days.getDate();

                pickmeup(".calendars").set_date([plus_7_days, new Date()]);

                eventBus.$emit("AttenDateAnalitic", data);
                $(".btn-calendars").removeClass("active");
                $(this).addClass("active");

            });

            //30 дней
            $(".calendars-month").click(function () {
                let data = {};
                let plus_7_days = new Date();
                data.date_end  =
                    plus_7_days.getFullYear() +
                    "-" +
                    (plus_7_days.getMonth() + 1) +
                    "-" +
                    plus_7_days.getDate();
                plus_7_days.setDate(plus_7_days.getDate() -30);
                data.date_start =
                    plus_7_days.getFullYear() +
                    "-" +
                    (plus_7_days.getMonth() + 1) +
                    "-" +
                    plus_7_days.getDate();

                pickmeup(".calendars").set_date([plus_7_days, new Date()]);
                eventBus.$emit("AttenDateAnalitic", data);
                $(".btn-calendars").removeClass("active");
                $(this).addClass("active");
            });

            //90 дней
            $(".calendars-decade").click(function () {
                let data = {};
                let plus_7_days = new Date();
                data.date_end  =
                    plus_7_days.getFullYear() +
                    "-" +
                    (plus_7_days.getMonth() + 1) +
                    "-" +
                    plus_7_days.getDate();
                plus_7_days.setDate(plus_7_days.getDate() -90);
                data.date_start =
                    plus_7_days.getFullYear() +
                    "-" +
                    (plus_7_days.getMonth() + 1) +
                    "-" +
                    plus_7_days.getDate();

                pickmeup(".calendars").set_date([plus_7_days, new Date()]);
                
                eventBus.$emit("AttenDateAnalitic", data);
                $(".btn-calendars").removeClass("active");
                $(this).addClass("active");
            });


        },
    };
</script>

<style>
    .btn-calendars:focus{
        box-shadow: none !important;
        outline: none !important;
        border:none !important;
    }
    .btn-calendars.active {
        background: rgb(242, 153, 74) !important;
        border-color: rgb(242, 153, 74) !important;
        color: #fff;
        box-shadow: none !important;
        outline: none !important;
    }

    .calendars .pickmeup {
        background: transparent;
    }

    .calendars .pickmeop *,
    .calendar .pickmeop * {
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    .calendars .pickmeop.pmu-flat,
    .calendar .pickmeop.pmu-flat {
        position: relative;
    }

    .calendars .pickmeop.pmu-hidden,
    .calendar .pickmeop.pmu-hidden {
        display: none;
    }

    .calendars .pickmeop .pmu-instance,
    .calendar .pickmeop .pmu-instance {
        display: inline-block;
        height: auto;
        padding: 0.5em;
        text-align: center;
        width: 100%;
    }

    .calendars .pickmeop .pmu-instance .pmu-button,
    .calendar .pickmeop .pmu-instance .pmu-button {
        font-size: 12px;
        line-height: 24px;
        text-align: center;
        color: #828282;
    }

    .calendars .pickmeop .pmu-instance .pmu-month,
    .calendar .pickmeop .pmu-instance .pmu-month {
        font-weight: bold;
        color: #f2994a;
        line-height: 32px;
    }

    .calendars .pickmeop .pmu-instance .pmu-today,
    .calendar .pickmeop .pmu-instance .pmu-today {
        background: #f2994a;
        border-radius: 50%;
        color: #fff;
    }

    .calendars .pickmeop .pmu-instance .pmu-not-in-month,
    .calendar .pickmeop .pmu-instance .pmu-not-in-month {
        color: #666;
    }

    .calendars .pickmeop .pmu-instance .pmu-disabled,
    .calendars .pickmeop .pmu-instance .pmu-disabled:hover,
    .calendar .pickmeop .pmu-instance .pmu-disabled,
    .calendar .pickmeop .pmu-instance .pmu-disabled:hover {
        color: #333;
        cursor: default;
    }

    .calendars .pickmeop .pmu-instance .pmu-selected,
    .calendar .pickmeop .pmu-instance .pmu-selected {
        background: #f2994a;
        border-radius: 50%;
        color: #fff;
    }

    .calendars .pickmeop .pmu-instance .pmu-not-in-month.pmu-selected,
    .calendar .pickmeop .pmu-instance .pmu-not-in-month.pmu-selected {
        background: #f2994a;
    }

    .calendars .pickmeop .pmu-instance nav,
    .calendar .pickmeop .pmu-instance nav {
        color: #eee;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        line-height: 14px;
    }

    .calendars .pickmeop .pmu-instance nav *:first-child :hover,
    .calendar .pickmeop .pmu-instance nav *:first-child :hover {
        color: #88c5eb;
    }

    .calendars .pickmeop .pmu-instance nav .pmu-prev,
    .calendars .pickmeop .pmu-instance nav .pmu-next,
    .calendar .pickmeop .pmu-instance nav .pmu-prev,
    .calendar .pickmeop .pmu-instance nav .pmu-next {
        color: #f2994a;
        height: 0em;
        width: 1em;
        font-size: 40px;
    }

    .calendars .pickmeop .pmu-instance .pmu-years *,
    .calendars .pickmeop .pmu-instance .pmu-months *,
    .calendar .pickmeop .pmu-instance .pmu-years *,
    .calendar .pickmeop .pmu-instance .pmu-months * {
        display: inline-block;
        line-height: 3.6em;
        width: 3.5em;
    }

    .calendars .pickmeop .pmu-instance .pmu-day-of-week,
    .calendar .pickmeop .pmu-instance .pmu-day-of-week {
        width: 15px;
        font-family: Roboto;
        font-style: normal;
        font-weight: bold;
        font-size: 12px;
        border-bottom: 1px solid #ffffff;
        text-align: center;
        color: #828282;
        width: 100%;
    }

    .calendars .pickmeop .pmu-days,
    .calendar .pickmeop .pmu-days {
        margin: 6px 0;
        border-bottom: 1px solid #ffffff;
    }

    .calendars .pickmeop .pmu-instance .pmu-day-of-week *,
    .calendars .pickmeop .pmu-instance .pmu-days *,
    .calendar .pickmeop .pmu-instance .pmu-day-of-week *,
    .calendar .pickmeop .pmu-instance .pmu-days * {
        display: inline-block;
        width: 24px;
        margin: 1px 5.5px;
    }

    .calendars .pickmeop .pmu-instance .pmu-day-of-week *,
    .calendar .pickmeop .pmu-instance .pmu-day-of-week * {
        padding: 5px;
    }

    .calendars .pmu-month .pickmeop .pmu-instance:first-child .pmu-prev,
    .calendars .pmu-month .pickmeop .pmu-instance:last-child .pmu-next,
    .calendar .pmu-month .pickmeop .pmu-instance:first-child .pmu-prev,
    .calendar .pmu-month .pickmeop .pmu-instance:last-child .pmu-next {
        display: block;
    }

    .calendars .pickmeop .pmu-instance:first-child .pmu-month,
    .calendars .pmu-month .pickmeop .pmu-instance:last-child .pmu-month,
    .calendar .pickmeop .pmu-instance:first-child .pmu-month,
    .calendar .pmu-month .pickmeop .pmu-instance:last-child .pmu-month {
        width: 13em;
    }

    .calendars .pickmeop .pmu-instance:first-child:last-child .pmu-month,
    .calendar .pickmeop .pmu-instance:first-child:last-child .pmu-month {
        width: 100%;
    }

    .calendars .pickmeop:not(.pmu-view-days) .pmu-days,
    .calendars .pickmeop:not(.pmu-view-days) .pmu-day-of-week,
    .calendars .pickmeop:not(.pmu-view-months) .pmu-months,
    .calendars .pickmeop:not(.pmu-view-years) .pmu-years,
    .calendar .pickmeop:not(.pmu-view-days) .pmu-days,
    .calendar .pickmeop:not(.pmu-view-days) .pmu-day-of-week,
    .calendar .pickmeop:not(.pmu-view-months) .pmu-months,
    .calendar .pickmeop:not(.pmu-view-years) .pmu-years {
        display: none;
    }
</style>

