// Book now
$(document).ready(function () {

    $(".btn_book").click(function () {
        console.log("Book");
        if ($('.reservation_date').attr('data') == undefined) {
            iziToast.error({
                title: 'Rental Date',
                message: 'Please select rental period',
                position: 'topRight'
            });
        } else {
            // if (is_approved == 0) {
            //     iziToast.error({
            //         title: 'Permission',
            //         message: 'Profile not approved.',
            //         position: 'topRight'
            //     });
            //     return false;
            // }
            $('body').addClass("show-book");
        }

    });
    $(".bookitem button").click(function () {
        $('body').removeClass("show-book");
    });
});

// payment
$(document).ready(function () {
    $(".ch_out").click(function () {
        if ($('.reservation_date').attr('data') == undefined) {
            iziToast.error({
                title: 'Rental Date',
                message: 'Please select rental period',
                position: 'topRight'
            });
        } else {
            //$('body').addClass("show-pay");
        }
    });
    // $(".paymentitem button").click(function() {
    //     $('body').removeClass("show-pay");
    // });
});

//const min_date = $.cookie('from_date')+' hh:mm A';

$('.datetimePicker').daterangepicker({
    singleDatePicker: true,
    timePicker: true,
    locale: {
        format: 'YYYY-MM-DD hh:mm A'
    },
    //minDate: min_date
});

jQuery(document).on("click", 'input[name="security_option"]', function () {
    $('input[name="security_option"]').attr("checked", false);
    $(this).attr("checked", true);
    $(".set_security_text").text($("input[name='security_option']:checked").attr('data'));
    $(".set_security_amount").text('$' + $("input[name='security_option']:checked").val());
    if ($("input[name='security_option']:checked").attr('data') == 'Security')
        $('input[name="option"]').val("security");
    else
        $('input[name="option"]').val("insurance");

    var getdays = 1;
    if ($("input[name='security_option']:checked").attr('days')) {
        getdays = $("input[name='security_option']:checked").attr('days');
    }
    var rent_price = parseInt(rentPrice) * parseInt(getdays);
    get_transFee(rentPrice, getdays, rent_price);
});

$('#checkout').submit(function (e) {
    e.preventDefault();
    var formData = new FormData($('form#checkout').get(0));
    $(".ch_out").attr('disabled', true);
    $(".show-loader").show();
    $.ajax({
        type: 'POST',
        url: $("#checkout").attr("action"),
        data: formData,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function (result) {
            //console.log("result",result);
            $(".ch_out").attr('disabled', false);
            $(".show-loader").hide();
            if (result.status == true) {
                $("#payment").html(result.data.html);
                $('body').addClass("show-pay");
                loadPaymentScript();
            } else if (result.status == false) {
                //window.location.replace(APP_URL+'/product/'+result.data);
                iziToast.error({
                    title: result.title,
                    message: result.message,
                    position: 'topRight'
                });
            }
        }
    });
});

jQuery(document).on("click", ".paymentitem button", function () {
    $('body').removeClass("show-pay");
});


function loadPaymentScript() {
    var script = document.createElement('script');
    script.setAttribute('type', 'text/javascript');
    script.setAttribute('src', paymentjs);
    jQuery("body").append(script);
}


/* Copy location */
$(document).on('click', '.copy-address', function () {
    let temp = $("<input>"),
        url = jQuery(this).attr('data');
    $("body").append(temp);
    temp.val(url).select();
    document.execCommand("copy");
    temp.remove();
    iziToast.success({
        title: 'Pick up location',
        message: 'location copied successfully.',
        position: 'topRight'
    });
})