$(document).ready(function () {

    $('#addIngredientForm').submit(function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        axios.post('/bars/ingredient', formData)
            .then(response => {
                if (response.data.success) {
                    location.reload();
                }
            })
            .catch(function (error) {
                alert('Ошибка.Попробуйте позже')
            });
    });


    if ($('#IngredientTable').length) {
        if (LanguneThisJs == 'ua') {
            var url = "//cdn.datatables.net/plug-ins/1.10.20/i18n/Ukrainian.json";
        } else {
            var url = "//cdn.datatables.net/plug-ins/1.10.20/i18n/Russian.json";
        }
        $('#IngredientTable').DataTable({
            "language": {
                "url": url
            },
            "pageLength": 50,
            "columnDefs": [{
                "targets": [1, 2],
                "orderable": false,
                "searchable": false
            }, ]
        });
    };

    if ($('.DataTable').length) {
        if (LanguneThisJs == 'ua') {
            var url = "//cdn.datatables.net/plug-ins/1.10.20/i18n/Ukrainian.json";
        } else {
            var url = "//cdn.datatables.net/plug-ins/1.10.20/i18n/Russian.json";
        }
        $('.DataTable').DataTable({
            "language": {
                "url": url
            },
            "pageLength": 50,
        });
    };



    $('.select2').select2();

    // добавить составляющую
    $('#addProdIng').click(function (e) {
        e.preventDefault();
        let $html = $('#invoiceItemtmpl').html();
        $('#addPurchaseinvoiceItem').append($html);
        $('#addPurchaseinvoiceItem .select2Din').select2();
    });

    // добавить продукт
    $('#addProd').click(function (e) {
        e.preventDefault();
        let $html = $('#productItemtmpl').html();
        $('#addPurchaseProduct').append($html);
        $('#addPurchaseProduct .select2Din').select2();
    });

    $('body').on('click', '.removeItem', function (event) {
        event.preventDefault();
        $(this).parents('.addPurchaseinvoiceItem').remove();
    });


    // открыть заказ
    $('#openOrderSubmit').click(function () {
        $('#alert-warning').addClass('hidden');
        $('[name="count_ingredients[]"]').each(function () {
            if ($(this).val() == "") {
                $('#alert-warning').removeClass('hidden');
                $("html, body").animate({
                        scrollTop: $('#alert-warning').offset().top + "px"
                    },
                    500, "linear");
                return false;
            }
        });
        $('[name="count_stocks[]"').each(function () {
            if ($(this).val() == "") {
                $('#alert-warning').removeClass('hidden');
                $("html, body").animate({
                        scrollTop: $('#alert-warning').offset().top + "px"
                    },
                    500, "linear");
                return false;
            }
        });

        if ($('[name="kofeinyi_apparat"]').val() == "") {
            $('#alert-warning').removeClass('hidden');
            $("html, body").animate({
                    scrollTop: $('#alert-warning').offset().top + "px"
                },
                500, "linear");
        }

    })

    // закрытие  заказа
    $('#closeOrderSubmit').click(function () {
        $('#alert-warning').addClass('hidden');
        $('[name="count_ingredients[]"]').each(function () {
            if ($(this).val() == "") {
                $('#alert-warning').removeClass('hidden');
                $("html, body").animate({
                        scrollTop: $('#alert-warning').offset().top + "px"
                    },
                    500, "linear");
                return false;
            }
        });
        $('[name="count_stocks[]"').each(function () {
            if ($(this).val() == "") {
                $('#alert-warning').removeClass('hidden');
                $("html, body").animate({
                        scrollTop: $('#alert-warning').offset().top + "px"
                    },
                    500, "linear");
                return false;
            }
        })

        if ($('[name="kofeinyi_apparat"]').val() == "") {
            $('#alert-warning').removeClass('hidden');
            $("html, body").animate({
                    scrollTop: $('#alert-warning').offset().top + "px"
                },
                500, "linear");
        }

    })

    ///закрытие смены
    $('#closeFormOrder').submit(function (event) {
        event.preventDefault();
        // $('#closeOrderSubmit').prop('disabled',true);
        let formData = new FormData(this);
        axios.post('/ajax/orderCloseValidate', formData)
            .then(response => {
                let tbodyCloseOrder = $('#tbodyCloseOrder');
                $('#alert-error').addClass('hidden');
                tbodyCloseOrder.html('');

                if (!response.data.success) {
                    if (response.data.results.ingredients) {
                        response.data.results.ingredients.forEach(function (element, index) {
                            tbodyCloseOrder.append('<tr>' +
                                '<td>' + element.title + '</td>' +
                                '<td>' + element.oldCount + '</td>' +
                                '<td>' + element.thisCount + '</td>' +
                                '</tr>');
                        });
                    }
                    if (response.data.results.stocks) {
                        response.data.results.stocks.forEach(function (element, index) {
                            tbodyCloseOrder.append('<tr>' +
                                '<td>' + element.title + '</td>' +
                                '<td>' + element.oldCount + '</td>' +
                                '<td>' + element.thisCount + '</td>' +
                                '</tr>');
                        });
                    }
                    // кавовый аппарат проверяем
                    if (response.data.results.coffee) {
                        tbodyCloseOrder.append('<tr>' +
                            '<td>' + response.data.results.coffee[0] + '</td>' +
                            '<td>' + response.data.results.coffee[1] + '</td>' +
                            '<td>' + response.data.results.coffee[2] + '</td>' +
                            '</tr>');
                    }
                    $('#alert-error').removeClass('hidden');
                    $("html, body").animate({
                            scrollTop: $('#alert-error').offset().top + "px"
                        },
                        500, "linear");
                    $('#order_OrderForced_Conteer').removeClass('hidden');
                }
                if (response.data.success) {
                    axios.post('/ajax/orderClose', formData)
                        .then(response2 => {
                            location.href = response2.data.url
                        })
                        .catch(function (error) {
                            alert('Ошибка.Попробуйте позже')
                        });
                }
            })
            .catch(function (error) {
                alert('Ошибка.Попробуйте позже1');
            });
    });
    $('#closeFormOrderForced').submit(function (e) {
        e.preventDefault();
        $('#closeFormOrderButton').prop('disabled', true);
        let form = document.getElementById('closeFormOrder');
        let formData = new FormData(form);
        formData.append('Forced', true);
        axios.post('/ajax/orderClose', formData)
            .then(response2 => {
                location.href = response2.data.url
                $('#closeFormOrderButton').prop('disabled', false);
            })
            .catch(function (error) {
                alert('Ошибка.Попробуйте позже');
                $('#closeFormOrderButton').prop('disabled', false);
            });
    })


    $('.selectCustomer').select2();
    $('.selectCustomer').change(function () {
        let $selected = $(".selectCustomer option:selected");
        let name = $selected.data('name')
        let v = $selected.text();
        $('.selectedName').text(name);
        //************************************ 
        // document.getElementById('advanced-demo').value = item.getAttribute('data-langname');
        // document.getElementById('fb-root').innerHTML = 'id: ' + item.getAttribute('data-name') + ' (' + item.getAttribute('data-lang') + ') ';
        $('.gdd').val(name);
        // $('#customer2').val(name);
        $('.rads').show();
        $('#p1').show();
        $('#phons').val(v)

    })

    //****************** заказ на странице  столов ***********************************
    $('#SmsModalShow').click(function (e) {
        e.preventDefault();
        $('#SmsModal').modal('show');
    })
    $('#PayModalShow').click(function (e) {
        e.preventDefault();
        $('#PayModal').modal('show');
    })
    //****************** заказ на странице  столов end***********************************

})