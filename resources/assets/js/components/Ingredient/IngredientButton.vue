<template>
    <div class="text-center" style="margin-bottom: 1rem;">
        <button class="btn btn-primary" @click.prevent="save">{{lang}}</button>
    </div>
</template>
<script>
    export default {
        name: "IngredientButton",
        props: ['id','lang'],
        methods: {
            save(event) {
                let btn = event.target;
                let $btn = $(btn);
                btn.disabled = true;
                axios.put('/bars/ingredient/'+this.id, {
                    count: $btn.parents('tr').find('[name="count"]').val(),
                })
                    .then(response => {
                        if (response.data.success) {
                            this.$swal.fire({
                                type: "success",
                                title: "Збережено",
                                timer: 2500
                            });
                        }
                    })
                    .catch(function (error) {
                        this.$swal.fire({
                            type: "error",
                            title: "Помилка",
                            timer: 2500
                        });
                    })
                    .finally(function () {
                        btn.disabled = false;
                    });
            }
        }
    }
</script>
