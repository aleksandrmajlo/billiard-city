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



})

$(document).ready(function () {

    if ($('[name="act1"]').length) {
        let $act1 = $('[name="act1"]');
        $act1.change(function (e) {
            let selected = $('option:selected', $act1);
            let title = '№' + selected.attr('value') + ' от ' + selected.data('date');
            $('.act1_title').text(title).removeClass('hidden');
            $('.act1_user').text(selected.data('user')).removeClass('hidden');
            $('.act1_change').text(selected.data('change')).removeClass('hidden');
        }).trigger('change')
    }

    if ($('[name="act2"]').length) {
        let $act2 = $('[name="act2"]');
        $act2.change(function (e) {
            let selected = $('option:selected', $act2);
            let title = '№' + selected.attr('value') + ' от ' + selected.data('date');
            $('.act2_title').text(title).removeClass('hidden');
            $('.act2_user').text(selected.data('user')).removeClass('hidden');
            $('.act2_change').text(selected.data('change')).removeClass('hidden');
        }).trigger('change')
    }


});

$(document).ready(function () {

    $('.categoryDocSortOrder').click(function (e) {
        e.preventDefault();
        let sort = $(this).data('sort');
        $('[name="sort"]').val(sort);
        document.getElementById('categoryDocSortOrderForm').submit();
    });

    $('.typeDocSortOrder').click(function (e) {
        e.preventDefault();
        let sort = $(this).data('sort');
        $('[name="type"]').val(sort);
        document.getElementById('typeDocSortOrderForm').submit();
    });

    //модалка
    $('.modalShow').click(function (e) {
        e.preventDefault();
        $('.overlayDoc').addClass('target');
        $('#readOrderWin').addClass('target');
    })

    $('a[href="#win1"].liken__buttom').click(function (e) {
        e.preventDefault();
        $('.overlayDoc').addClass('target');
        $('#compareActWin').addClass('target');
    });

    $('#closeCompareActWin,.overlayDoc,#closeWin').click(function (e) {
        e.preventDefault();
        $('.overlayDoc').removeClass('target');
        $('#compareActWin').removeClass('target');
        $('#readOrderWin').removeClass('target');
    });

    // cайт бар
    $('#SidebarToggle').click(function () {
        setTimeout(() => {
            let bodyClass = $('body').hasClass('sidebar-collapse');
            axios.post('/ajax/setSidebarToggle', {
                type: bodyClass
            })
        }, 50);
    })

});