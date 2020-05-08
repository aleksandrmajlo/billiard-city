<template>
    <div class="timerBlock">
        <div class="block" v-if="days>0">
            <p class="digit">{{ days | two_digits }}</p>
        </div>
        <div class="spacer" v-if="days>0">:</div>
        <div class="block">
            <p class="digit">{{ hours | two_digits }}</p>
        </div>
        <div class="spacer">:</div>
        <div class="block">
            <p class="digit">{{ minutes | two_digits }}</p>
        </div>
        <div class="block hidden">
            <p class="digit">{{ seconds | two_digits }}</p>
        </div>
    </div>
</template>

<script>
    export default {
        name: "TimerTable",
        mounted() {
            if (this.pause == "-1") {
                window.setInterval(() => {
                    var TimezoneOffset = 2; // указать нужное смещение по Гринвичу, сюда вписывать ваш часовой пояс (+2)
                    var localTime = new Date();
                    var ms =
                        localTime.getTime() +
                        localTime.getTimezoneOffset() * 60000 +
                        TimezoneOffset * 3600000;
                    var time = new Date(ms);
                    this.now = Math.trunc(time.getTime() / 1000);
                }, 1000);
            }
        },
        props: ["table_id", "date", "pause"],
        data() {
            return {
                now: Math.trunc(new Date().getTime() / 1000)
            };
        },
        computed: {
            start() {
                return parseInt(this.date);
            },
            seconds() {
                return (this.now - this.start) % 60;
            },
            minutes() {
                return Math.trunc((this.now - this.start) / 60) % 60;
            },
            hours() {
                return Math.trunc((this.now - this.start) / 60 / 60) % 24;
            },
            days() {
                return Math.trunc((this.now - this.start) / 60 / 60 / 24);
            }
        }
    };
</script>
