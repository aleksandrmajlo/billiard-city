<template>
    <div class="billiard_block">
        <div class="billiard_table">
            <table>
                <tr>
                    <td colspan="4" style="width: 50%;  border-right: 1px solid #fff; ">
                        <p class="billiard_title">{{text1}}</p>
                    </td>
                    <td colspan="2" style="width: 50%;">
                        <p class="billiard_title tclose">{{text2}}</p>
                    </td>
                </tr>
                <tr>
                    <td>{{text3}}</td>
                    <td>{{text4}}</td>
                    <td>{{text5}}</td>
                    <td>{{text6}}</td>
                    <td>{{text3}}</td>
                    <td></td>
                </tr>
                <tr v-for="(table,index) in tables" :key="index">
                    <td v-if="table.reserv">
                        <span>{{table.reserv.number}}</span>
                        {{table.reserv.name}}
                    </td>
                    <td v-else></td>
                    <td v-if="table.reserv">
                         <template v-if="table.reserv.activePause">пауза</template>
                         <template v-else>{{text7}}</template>
                    </td>
                    <td v-else></td>
                    <td v-if="table.reserv">
                        {{table.reserv.minutes|minutes_houres}}
                    </td>
                    <td v-else></td>
                    <td v-if="table.reserv">{{table.reserv.priceOrderTotal}} ₴</td>
                    <td v-else></td>
                    <!--free-->
                    <td  v-if="table.free">
                        <span>{{table.free.number}}</span>
                    </td>
                    <td v-else></td>
                    <td v-if="table.free">
                        {{table.free.name}}
                    </td>
                    <td v-else></td>
                </tr>
            </table>
        </div>
    </div>
</template>
<script>
    export default {
        name: "ScreenTable",
        data(){
            return {
                tables:[],
                LanguneThis:'uk',
                lang:{
                    text1:{
                        ru:'Открытые столы',
                        uk:'Відкриті столи'
                    },
                    text2:{
                        ru:'Закрытые столы',
                        uk:'Закриті столи'
                    },
                    text3:{
                        ru:'стол',
                        uk:'стіл'
                    },
                    text4:{
                        ru:'Статус стола',
                        uk:'Статус столу'
                    },
                    text5:{
                        ru:'открыт время',
                        uk:'відкритий час'
                    },
                    text6:{
                        ru:'цена сейчас',
                        uk:'ціна зараз'
                    },
                    text7:{
                        ru:'открыт',
                        uk:'відкритий'
                    },

                },
            }
        },
        computed:{

            text1(){
                return this.lang.text1[this.LanguneThis]
            },

            text2(){
                return this.lang.text2[this.LanguneThis]
            },

            text3(){
                return this.lang.text3[this.LanguneThis]
            },

            text4(){
                return this.lang.text4[this.LanguneThis]
            },

            text5(){
                return this.lang.text5[this.LanguneThis]
            },

            text6(){
                return this.lang.text6[this.LanguneThis]
            },
            text7(){
                return this.lang.text7[this.LanguneThis]
            },

        },
        created(){
            // this.GetScreenTables();
            // setInterval(this.GetScreenTables, 60000);

        },
        mounted(){
            this.GetScreenTables();
            setInterval(this.GetScreenTables, 60000);
        },
        methods:{
            GetScreenTables(){
                axios.get('/GetScreenTables').then(response => {

                   this.tables=response.data.tables;
                })
            }
        }
    }
</script>
<style scoped>
   .radio{
        color: #fff;
   }
</style>