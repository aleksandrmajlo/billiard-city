<template>
    <div class="overClock">
        <a href="#" class="dropdown-toggle calen1" data-toggle="dropdown">
            <img src="/img/colendar.png" alt="colendar">
            <span class="data">{{Datt}}.{{Month}}.{{Year}}</span>
        </a>
        <a class="calen2" href="#">
            <img src="/img/time.png" alt="time">
            <span class="time">{{hours}}.{{minutes}}</span>
        </a>
    </div>
</template>

<script>
    export default {
        name: "ClockHeader",
        data() {
            return {
                Year: '',
                Month: '',
                Datt: '',
                hours: '',
                minutes: ''
            }
        },
        created() {
            setInterval(this.update, 1000);
            this.update();
        },
        methods: {

            update() {

                let date = new Date();
                this.Year = date.getFullYear();

                let month = date.getMonth();
                if (month < 10) month = '0' + (month+1);
                this.Month = month;

                let dat = date.getDate();
                if (dat < 10) dat = '0' + dat;
                this.Datt = dat;

                let hours = date.getHours();
                if (hours < 10) hours = '0' + hours;
                this.hours = hours;

                let minutes = date.getMinutes();
                if (minutes < 10) minutes = '0' + minutes;
                this.minutes = minutes;

            }
        }
    }
</script>

<style scoped lang="scss">
    .overClock {
        height: 100%;
        display: flex;
        a{
            padding: 21px 30px 15px;
        }

        a.calen1{
            img{
                vertical-align: text-top;
            }
        }
        a.calen2{
            img{
                vertical-align: bottom;
            }
        }

        span {
            color: rgb(0, 10, 26);
            font-size: 16px;
        }
    }
</style>