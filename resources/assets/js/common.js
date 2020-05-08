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

    if ($('.select2').length) {
        $('.select2').select2();
    }


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

    if ($('.selectCustomer').length) {
        $('.selectCustomer').select2();
        $('.selectCustomer').change(function () {
            let $selected = $(".selectCustomer option:selected");
            let name = $selected.data('name')
            let v = $selected.text();
            $('.selectedName').text(name);
            //************************************ 
            $('.gdd').val(name);
            $('.rads').show();
            $('#p1').show();
            $('#phons').val(v);
        })
    }


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

    //модалка
    $('.modalShow').click(function (e) {
        e.preventDefault();
        $('#win1').addClass('target')
    })
    $('#closeWin,#win1').click(function (e) {
        e.preventDefault();
        $('#win1').removeClass('target');
    })

})