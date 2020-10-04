<template>
    <div class="col-lg-6 col-md-12">
        <div class="user_top user_top-righit">
            <form @submit.prevent>
                <input v-model="search" type="text" placeholder=''>
                <button @click="searchBooking" type="submit">
                    <img src="/img/search.png" alt="search">
                </button>
            </form>
            <button @click.prevent="addBooking" class="append">Добавити</button>
        </div>
        <div class="booking__table">
            <button v-for="(booking,index) in bookings" :key="index" @click.prevent="showReadForm(booking)"  class="booking__table-item" >
                <p>{{booking.from}}</p>
                <p>
                    <img src="/img/user.png" alt="number">
                    <strong class="name-user">{{booking.lastname}} {{booking.name}}</strong>
                </p>
                <p>{{booking.from_time}} - {{booking.to_time}}</p>
                <p>№ {{booking.table_number}}</p>
                <span v-if="booking.status==1"></span>
            </button>
        </div>
    </div>
</template>

<script>
    import {eventBus} from "../../app";
    export default {
        name: "SearchResults",
        data(){
            return {
                date_start:null,
                date_end:null,
                search:"",
                searchActive:false,
                bookings:[]
            }
        },
        created(){
            let today = new Date();
            let Year=today.getFullYear();
            let Month=today.getMonth()+1;
            let day=today.getDate();

            this.getBookings(Year+'-'+Month+"-"+day);
            this.date_start=Year+'-'+Month+"-"+day;
            // обновить список брони
            eventBus.$on('updateBookings', (ar_date=false) => {
                if(ar_date){
                    this.date_start=ar_date.date_start;
                    this.date_end=ar_date.date_end;
                    this.searchActive=false;
                }
                if(this.searchActive){
                    this.searchBooking();
                }else{
                    this.getBookings(this.date_start,this.date_end);
                }
            });

        },
        methods:{
            addBooking(){
                eventBus.$emit('showAddBookingForm')
            },
            // обновить
            showReadForm(booking){
                eventBus.$emit('showReadFormOn',booking)
            },
            getBookings(date_start,date_end=null){
                axios
                    .post("/booking_ajax/bookings",{date_start:date_start,date_end:date_end})
                    .then(response => {
                        this.bookings = response.data.bookings;
                    })
                    .catch(error => {
                    })
                    .finally(function () {
                    });

            },
            searchBooking(){
                if(this.search!==""){
                    this.searchActive=true;
                    $('.pmu_button').removeClass('active');
                    // pickmeup('.range').set_date([new Date]);
                    axios
                        .post("/booking_ajax/searchBooking",{search:this.search})
                        .then(response => {
                            this.bookings = response.data.bookings;
                        })
                        .catch(error => {
                        })
                        .finally(function () {
                        });
                }
            }
        }
    }
</script>
