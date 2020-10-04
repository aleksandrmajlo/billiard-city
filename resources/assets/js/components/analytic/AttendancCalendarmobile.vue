<template>
    <div class="analytics__range ">
        <nav class="calendars-nav">
            <button class="btn btn-calendars analytics-week calendars-week_mob">{{$t('calednar7')}}</button>
            <button class="btn btn-calendars analytics-month calendars-month_mob">{{$t('calednar30')}}</button>
            <button class="btn btn-calendars analytics-decade calendars-decade_mob">{{$t('calednar3month')}}</button>

        </nav>
        <div class="range"></div>
        <div class="calendars__wrap min">
            <div class="calendar"></div>

        </div>
    </div>
</template>

<script>
    import pickmeup from "../../plugins/pickmeup.js";
    import {eventBus} from "../../app";
    export default {
        name: "AttendancCalendarmobile",
        mounted() {
            let self = this;

            pickmeup(".calendar", {
                flat: true,
                mode: "range",
                class_name: "pickmeop",
            });

            pickmeup(".range", {
                flat: true,
                mode: "range",
            });
            //1 дней
            /*
            $(".calendars-today_mob").click(function () {
                let data = {};
                let plus_7_days = new Date();
                data.date_start =
                    plus_7_days.getFullYear() +
                    "-" +
                    (plus_7_days.getMonth() + 1) +
                    "-" +
                    plus_7_days.getDate();
                pickmeup(".calendar").set_date([new Date()]);
                pickmeup(".range").set_date([new Date()]);
                data.date_end = null;
                eventBus.$emit("AttenDateAnalitic", data);
                $(".btn-calendars").removeClass("active");
                $(this).addClass("active");
            });
            */
            // 7 дней
            $(".calendars-week_mob").click(function () {
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

                pickmeup(".calendar").set_date([plus_7_days, new Date()]);
                pickmeup(".range").set_date([plus_7_days, new Date()]);

                eventBus.$emit("AttenDateAnalitic", data);
                $(".btn-calendars").removeClass("active");
                $(this).addClass("active");

            });

            //30 дней
            $(".calendars-month_mob").click(function () {
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

                pickmeup(".calendar").set_date([plus_7_days, new Date()]);
                pickmeup(".range").set_date([plus_7_days, new Date()]);

                eventBus.$emit("AttenDateAnalitic", data);
                $(".btn-calendars").removeClass("active");
                $(this).addClass("active");
            });

            //90 дней
            $(".calendars-decade_mob").click(function () {
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

                pickmeup(".calendar").set_date([plus_7_days, new Date()]);
                pickmeup(".range").set_date([plus_7_days, new Date()]);

                eventBus.$emit("AttenDateAnalitic", data);
                $(".btn-calendars").removeClass("active");
                $(this).addClass("active");

            });

        }
    }
</script>

<style >
    @media (max-width: 454px){

        .calendars__wrap {
            margin-bottom: 20px;
        }

    }

    .calendars__wrap .pickmeup {
        background: transparent;
    }
    .range .pickmeup .pmu-instance {
        height: 462px;
        padding: .5625em;
        text-align: center;
        width: 455px;
    }

    .range .pickmeup {
        background: #ECF0F5;
        box-shadow: 0px 3px 7px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
    }

    .range .pickmeup .pmu-instance .pmu-day-of-week {
        color: #999;
        cursor: default;
        margin: 0 6px;
    }

    .range .pickmeup .pmu-instance .pmu-day-of-week * {
        width: 50px;
        margin: 0 6.5px;
        font-size: 16px;
        color: #F2994A;
    }

    .range .pickmeup .pmu-instance:first-child:last-child .pmu-month {
        font-weight: bold;
        font-size: 16px;
        line-height: 19px;
        color: #F2994A;
        text-shadow: 0px 3px 7px rgba(0, 0, 0, 0.1);
    }

    .range .pickmeup .pmu-instance:first-child .pmu-prev.pmu-button,
    .range .pickmeup .pmu-instance:last-child .pmu-next.pmu-button {
        display: block;
        font-size: 40px;
        text-align: center;
        color: #F2994A;
        width: auto;
        height: auto;
        border: none;
        line-height: 35px;
        position: relative;
        margin-left: 10px;
    }

    .range .pickmeup .pmu-instance .pmu-month.pmu-button {
        border: none;
        margin: 0px;
    }

    .range .pickmeup .pmu-instance .pmu-month.pmu-button:hover,
    .range .pickmeup .pmu-instance:first-child .pmu-prev.pmu-button:hover,
    .range .pickmeup .pmu-instance:last-child .pmu-next.pmu-button:hover {
        background: transparent;
        box-shadow: none;
    }

    .range .pickmeup .pmu-instance .pmu-button {
        font-size: 16px;
        text-align: center;
        color: #828282;
        width: 50px;
        height: 50px;
        border: 1px solid #FFFFFF;
        box-sizing: border-box;
        border-radius: 5px;
        margin: 5.5px;
        line-height: 50px;
        position: relative;
    }

    .range .pickmeup .pmu-instance .pmu-button:hover,
    .range .pickmeup .pmu-instance .pmu-selected {
        background: #F2994A;
        box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.2);
        border-radius: 5px;
        color: #FFFFFF;
    }

    .range .pickmeup .pmu-instance:first-child:last-child .pmu-month {
        width: 8em;
        padding: 16px 0;
    }

    .range .pickmeup .pmu-instance .pmu_button {
        background: #ECF0F5;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.06), 0px -4px 6px rgba(255, 255, 255, 0.5);
        border-radius: 5px;
        color: #828282;
        width: 74px;
        height: 30px;
        line-height: 31px;
        margin-top: 10px;
        margin-left: 10px;
        cursor: pointer;
    }

    .range .pickmeup .pmu-instance .pmu_button:hover {
        background: #F2994A;
        color: #fff;
    }

    .range .pickmeup .pmu-instance .pmu-today {
        background: #F2994A;
        color: #fff;
    }
    .range .pickmeup .pmu-instance .pmu-one,
    .range .pickmeup .pmu-instance .pmu-two,
    .range .pickmeup .pmu-instance .pmu-three{
         display:none !important;
    }

</style>