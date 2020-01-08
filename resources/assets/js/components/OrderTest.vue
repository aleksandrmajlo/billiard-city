<template>
     <form :action="'/orderbil-closed/'+id" method="get">
         <h5 class="mt-5 text-center text-bold">Test Date</h5>
         <input type="hidden" name="start" :value="startTime"/>
         <input type="hidden" name="end" :value="endTime"/>
         <div class="form-group">
             <label>Start</label>
             <input class="form-control" v-model="start" required type="datetime-local">
         </div>
         <div class="form-group">
             <label>End</label>
             <input class="form-control" v-model="end" required type="datetime-local">
         </div>
         <button class="btn btn-primary" type="submit">Send</button>
     </form>
</template>

<script>
    export default {
        name: "OrderTest",
        data(){
            return {
                start:'',
                end:'',
            }
        },
        props:['id'],
        computed:{

            startTime(){
                let d=new Date(this.start);
                return d.getTime();
            },
            endTime(){
                let d=new Date(this.end);
                return d.getTime();
            },

        }
    }
</script>

<style scoped>

</style>