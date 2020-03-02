$(document).ready(function () {

    $("#loginUser").click(function () {
        $('.logouts').toggle();
    });
    $(".id3").click(function () {
        var idOrder = ($(this).data('order'));
        $('#orderid').val(idOrder);
    });
    $(".phone_mask").mask("+38 (999) 999-99-99");

    $('.addproduct').click(function () {
        var countid = this.id;
        var countid2 = Number(countid) + 1;
        // $("#sel1").clone().appendTo("#select_apend").val(0).attr("name", "product" + countid2);

        $("#sel2").clone().appendTo("#input_apend").val(1).attr("name", "count" + countid2);

        $("#del").append("<a class='btn btn-sm btn-app dels' id='" + countid2 + "' style='height: 44px;'><i class='fa fa-trash'></i></a>");

        $("#sel1").clone().addClass('js-example-basic-single2').attr("name", "count" + countid2).attr("id", "sel" + countid2).appendTo("#select_apend").val(0).attr("name", "product" + countid2).wrap("<div class='new" + countid2 + "'></div>");

        $('.js-example-basic-single2').select2({
            placeholder: 'Добавить товар'
        });

        $('.js-example-basic-single').select2({
            placeholder: 'Добавить товар'
        });
        $('.addproduct').attr("id", countid2);
    });
    $(".radio_option").change(function () {
        $('.reservid').hide();
        if ($('#test2').prop("checked")) {
            $('.customersd').fadeIn(300);
            $('#b1').hide();
        }
    });
    $(".radio_option2").change(function () {
        $('.reservid').show();
        $('.customersd').fadeOut(300);
        $('#b1').show();
    });

    $("#phons").on('keyup input', function () {
        var text = $('#phons').val();
        $('#ph').val(text);
    });

    $(document).on('click', '.dels', function () {
        $("select[name=product" + this.id + "]").remove();
        $(".new" + this.id).remove();

        $("input[name=count" + this.id + "]").remove();
        $("#" + this.id).hide();
    });


    $('#chechcode').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            dataType: 'json',
            type: "POST",
            url: '/checkCode',
            data: $('#chechcode').serialize(),
            success: function (msg) {
                if (msg == 1) {
                    $('#b1').show();
                    $('#createSend').show();
                    $('#formaphonsend2').hide();
                    $('#formaphonsend').hide();
                    $("#msg").hide();
                } else {
                    $("#msg").append("<div class='red'>Відповідь: код не вірний. Перевірте код або перезавантажте сторінку і попорбуйте ще раз. </div>");
                }

            }
        });
    });

    $('#contactform').on('submit', function (e) {
        e.preventDefault();
        console.log($('#contactform'))
        $.ajax({
            dataType: 'json',
            type: "POST",
            url: '/generateCode',
            data: $('#contactform').serialize(),
            success: function (msg) {

                $("#msg").append("<div> id: " + msg + ". </div>");
                $("#cod").val(msg);
                $('#formaphonsend').hide();
                $('#formaphonsend2').show();
            }
        });
    });

    $(".addCategory").click(function () {
        var title = ($(this).data('title'));
        var id = ($(this).data('id'));
        var image = ($(this).data('image'));
        if (typeof image !== "undefined") {
            $('#uploadImage').attr('src', image);
            $('#uploadImage').parent().removeClass('hidden');
        } else {
            $('#uploadImage').parent().addClass('hidden');
        }
        console.log(image)
        $('.idcatecory').val(id);
        $('.titlecatecory').val(title);
    });


    $(".addTable").click(function () {

        var min = ($(this).data('min'));
        var max_min_night = ($(this).data('max_min_night'));
        var max = ($(this).data('max'));
        var id = ($(this).data('id'));
        var number = ($(this).data('number'));
        var image = ($(this).data('image'));
        console.log(image)

        var socket = ($(this).data('socket'));
        var rele = ($(this).data('rele'));
        $("#socket").val(socket);
        $('#table_max_min_night').val(max_min_night);
        $('#table_min').val(min);
        $('#table_max').val(max);
        $('#table_id').val(id);
        $('#rele').val(rele);

        $('#table_number').val(number);
        if (image !== "") {
            $('#conteerTableImage').removeClass('hidden')
                .attr('src', image);
        } else {
            $('#conteerTableImage').addClass('hidden')
                .attr('src', '');
        }

    });

    //добавить товар


});
$(window).on('load', function () {

    if ($('.js-example-basic-single').length) {
        $('.js-example-basic-single').select2({
            tags: true
        });
    }

    if ($('.js-example-basic-single2').length) {
        $('.js-example-basic-single2').select2({
            placeholder: 'Добавить товар'
        });
    }

});