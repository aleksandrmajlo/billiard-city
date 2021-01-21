<template>
    <div>
        <div id="range" class="range"></div>
    </div>
</template>
<script>
    import pickmeup from "../../plugins/pickmeup.js";
    import {eventBus} from "../../app";

    export default {
        name: "CalendarBooking",
        data() {
            return {
                bookings: [],
            };
        },

        created() {
            eventBus.$on('UpdateCalendarDate', () => {
                setTimeout(() => {
                    this.SetActiveDay()
                }, 10)
            })
        },
        mounted() {
            let self = this;
            pickmeup(".range", {
                flat: true,
                mode: "range",
            });

            //***********************
            //1 дней
            $(".pmu-one").click(function () {
                let data = {};
                let plus_7_days = new Date();
                data.date_start =
                    plus_7_days.getFullYear() +
                    "-" +
                    (plus_7_days.getMonth() + 1) +
                    "-" +
                    plus_7_days.getDate();
                pickmeup(".range").set_date([new Date()]);
                data.date_end = null;
                data.calendar = true;
                eventBus.$emit("updateBookings", data);
                $(".pmu_button").removeClass("active");
                $(this).addClass("active");
            });

            // 7 дней
            $(".pmu-two").click(function () {
                let data = {};
                let plus_7_days = new Date();
                data.date_start =
                    plus_7_days.getFullYear() +
                    "-" +
                    (plus_7_days.getMonth() + 1) +
                    "-" +
                    plus_7_days.getDate();
                plus_7_days.setDate(plus_7_days.getDate() + 7);
                data.date_end =
                    plus_7_days.getFullYear() +
                    "-" +
                    (plus_7_days.getMonth() + 1) +
                    "-" +
                    plus_7_days.getDate();
                pickmeup(".range").set_date([new Date(), plus_7_days]);
                data.calendar = true;
                eventBus.$emit("updateBookings", data);
                $(".pmu_button").removeClass("active");
                $(this).addClass("active");
            });

            //30 дней
            $(".pmu-three").click(function () {
                let data = {};
                let plus_7_days = new Date();
                data.date_start =
                    plus_7_days.getFullYear() +
                    "-" +
                    (plus_7_days.getMonth() + 1) +
                    "-" +
                    plus_7_days.getDate();
                plus_7_days.setDate(plus_7_days.getDate() + 30);
                data.date_end =
                    plus_7_days.getFullYear() +
                    "-" +
                    (plus_7_days.getMonth() + 1) +
                    "-" +
                    plus_7_days.getDate();
                pickmeup(".range").set_date([new Date(), plus_7_days]);
                eventBus.$emit("updateBookings", data);
                $(".pmu_button").removeClass("active");
                data.calendar = true;
                $(this).addClass("active");
            });

            document
                .getElementById("range")
                .addEventListener("pickmeup-change", function (e) {
                    let data = {};
                    data.date_start =
                        e.detail.date[0].getFullYear() +
                        "-" +
                        (e.detail.date[0].getMonth() + 1) +
                        "-" +
                        e.detail.date[0].getDate();
                    data.date_end =
                        e.detail.date[1].getFullYear() +
                        "-" +
                        (e.detail.date[1].getMonth() + 1) +
                        "-" +
                        e.detail.date[1].getDate();
                    data.calendar = true;
                    eventBus.$emit("updateBookings", data);
                    $(".pmu_button").removeClass("active");
                    self.ValidateThisDate(e.detail.date[0], e.detail.date[1]);


                });
            this.SetActiveDay();
        },
        methods: {
            SetActiveDay() {
                axios
                    .get("/booking_ajax/calendar_bookings")
                    .then((response) => {

                        this.bookings = response.data.bookings;
                        let month = $(".pmu-month").data("month");
                        let year = $(".pmu-month").data("year");
                        let date = new Date();
                        let day = date.getDate();
                        // let day = date.getMonth();
                        if (
                            typeof this.bookings[year] !== "undefined" &&
                            typeof this.bookings[year][month] !== "undefined"
                        ) {
                            this.bookings[year][month].forEach((el) => {
                                $(".pmu-days .pmu-button")
                                    .not(".pmu-not-in-month")
                                    .each(function () {
                                        let d = $(this).text();
                                        if ((parseInt(el) == parseInt(d)) && day <= parseInt(el)) {
                                            var count = $(this).attr('data-count');
                                            if (typeof count == "undefined") {
                                                $(this).attr('data-count', 1)
                                            } else {
                                                $(this).attr('data-count', (parseInt(count) + 1));
                                            }
                                            $(this).addClass('YesBooking')
                                        }
                                    });
                            });
                        }

                    })
                    .catch((error) => {
                    })
                    .finally(function () {
                    });


            },
            ValidateThisDate(date_start, date_end) {
                let start_time = new Date(
                    date_start.getFullYear(),
                    date_start.getMonth(),
                    date_start.getDate()
                ).getTime();
                let end_time = new Date(
                    date_end.getFullYear(),
                    date_end.getMonth(),
                    date_end.getDate()
                ).getTime();
                let this_date = new Date();
                let this_time = new Date(
                    this_date.getFullYear(),
                    this_date.getMonth(),
                    this_date.getDate()
                ).getTime();
                setTimeout(() => {
                    if (start_time <= this_time && end_time >= this_time) {
                        $(".pmu-today").removeClass("not_this");
                    } else {
                        $(".pmu-today").addClass("not_this");
                    }
                    this.SetActiveDay();
                }, 10);
            },
        },
    };
</script>
<style scoped>
    @import "../../../../../public/css/pickmeup.css";

    .range .pickmeup .pmu-instance .pmu_button.active {
        background: rgb(242, 153, 74);
        color: rgb(255, 255, 255);
    }

    .pmu-today.not_this {
        background: inherit !important;
        color: inherit !important;
    }
</style>
