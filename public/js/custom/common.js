// Ajax setup
jQuery.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
    }
});

function ajaxCall(url, method, params) {
    return new Promise((resolve, reject) => {
        jQuery.ajax({
            url: url,
            method: method,
            data: params,
            dataType: 'json',
            success: function (response) {
                resolve(response)
            },
            error: function (error) {
                reject(error)
            }
        })
    });
}

// End of the ajax

// read more and read less
jQuery('.read-more').click(function () {
    jQuery('.read-more-content').addClass('d-none');
    jQuery('.read-less-content').removeClass('d-none');
});

jQuery('.read-less').click(function () {
    jQuery('.read-less-content').addClass('d-none');
    jQuery('.read-more-content').removeClass('d-none');
});
// end of read more and read less section

// show file name when uploaded
jQuery(document).ready(function () {
    jQuery('[data-toggle="tooltip"]').tooltip();
    jQuery('input[type="file"]').change(function () {
        if (!jQuery(this).attr('multiple') && !jQuery(this).attr('data-order')) {
            if (this.files.length == 1 && this.files[0].name != '') {
                let fileName = this.files[0].name;
                let fileType = fileName.substring(fileName.lastIndexOf('.') + 1)
                fileName = fileName.length > 10 ? fileName.substring(0, 10) + '..' + fileType : fileName;
                jQuery(this).parent().find('span').eq(0).text(fileName);
            } else {
                jQuery(this).parent().find('span').eq(0).text('Browse File');
            }
            jQuery(this).focusout();
        }
    });
});
// end of the upload file

// Jquery custom validations
jQuery.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param)
}, 'File size must be less than {0}');

jQuery.validator.addMethod("numeric", function (value, element, regexp) {
    return value.match(regexp)
}, "Please check your input.");

jQuery.validator.addMethod("inarray", function (value, element, param) {
    return this.optional(element) || $.inArray(value, param) >= 0;
}, "Invalid selection");

jQuery.validator.addMethod("regex", function (value, element, regexp) {
    if (regexp.constructor != RegExp) {
        regexp = new RegExp(regexp);
    } else if (regexp.global) {
        regexp.lastIndex = 0;
    }

    return this.optional(element) || regexp.test(value);
}, "Invalid value");

jQuery.validator.addClassRules("required", {
    required: true,
    normalizer: function (value) {
        return jQuery.trim(value);
    }
});
// end of the jquery custom validations

// US phone number format
// jQuery('input[name="phone_number"]').keyup(function(e) {
//     if (e.keyCode == 8 || e.keyCode == 46) {
//         return false;
//     }

//     var regex =/^[0-9]*$/;          
//     var number = jQuery(this).val().split("-").join("");
//     number = number.split("(").join("");
//     number = number.split(")").join("");

//     if(!regex.test(number)){
//         number = number.split(e.key).join("");
//     }
//     if (number.length > 10) {
//         number.slice(0,-1);
//     }
//     var finalNumber = "";
//     if (0 < number.length) {
//         finalNumber = finalNumber + "(" + number.substring(0,3);
//     }
//     if (3 <= number.length) {
//         finalNumber = finalNumber + ")-" + number.substring(3, (number.length > 6 ? 6 : number.length));
//     }
//     if (6 < number.length) {
//         finalNumber = finalNumber + "-" + number.substring(6, (number.length > 10 ? 10 : number.length));
//     }

//     jQuery(this).val(finalNumber);  
// });
// End of US phone number format

// Daterange picker
function initDateRangePicker(elem, options = {}, dates = null) {
    // get data from and to attribute
    console.log("date picker");
    let dateFrom = jQuery(elem).attr('data-from');
    let dateTo = jQuery(elem).attr('data-to');
    if (dates) {
        options["isInvalidDate"] = function (ele) {
            var currDate = moment(ele._d).format('MM/DD/YYYY');
            return non_available_dates.indexOf(currDate) != -1;
        }
    }
    if (dateFrom == null || dateFrom == undefined || dateTo == null || dateTo == undefined) {
        // reset the startDate and endDate from options
        if (options.hasOwnProperty('startDate') && options.hasOwnProperty('endDate')) {
            delete options['startDate'];
            delete options['endDate'];
        }
        jQuery(elem).daterangepicker(options);
    } else {
        options['startDate'] = dateFrom;
        options['endDate'] = dateTo;
        jQuery(elem).daterangepicker(options);
    }

    jQuery(elem).on('apply.daterangepicker', function (ev, picker) {
        let fromDate, toDate = '';
        let setDateRange = '';
        if (!jQuery.isEmptyObject(options) && options.hasOwnProperty('locale') && options.locale.hasOwnProperty('format')) {
            fromDate = picker.startDate.format(options.locale.format);
            toDate = picker.endDate.format(options.locale.format);
            if (options.locale.hasOwnProperty('separator')) {
                setDateRange = fromDate + options.locale.separator + toDate;
            } else {
                setDateRange = fromDate + ' to ' + toDate;
            }
            jQuery(elem).attr('value', setDateRange);
            jQuery('.reservation_date').attr('value', setDateRange);
            jQuery('.reservation_date').attr('data', 1);
            //$.cookie("from_date", fromDate);
        } else {
            fromDate = picker.startDate.format('YYYY-MM-DD');
            toDate = picker.endDate.format('YYYY-MM-DD');
            setDateRange = fromDate + ' to ' + toDate;
            jQuery(elem).attr('value', setDateRange);
        }
        // set data attribute
        jQuery(elem).attr('data-from', fromDate);
        jQuery(elem).attr('data-to', toDate);
        var days = picker.endDate.diff(picker.startDate, "days") + 1;
        $("input[name='security_option']").attr('days', days);
        $("input[name='days']").val(days);
        if (rentPrice) {
            $(".amount_days").text('Amount (' + days + ' days)');
            var rent_price = parseInt(rentPrice) * parseInt(days);
            $(".total_amount").text('$' + rent_price + '.00');
            get_transFee(rentPrice, days, rent_price);
        }
    });

    jQuery(elem).on('cancel.daterangepicker', function (ev, picker) {
        jQuery(elem).removeAttr('value').removeAttr('data-from data-to');
    });
}
// end of the daterangepicker

function get_transFee(rentPrice, days, rent_amount) {
    if (rentPrice) {
        $.ajax({
            type: 'POST',
            url: APP_URL + '/transfee',
            data: { rentPrice: rentPrice, days: days },
            success: function (result) {
                //console.log("result",result);
                if (result.status == true) {
                    $(".trans_fee").text('$' + result.transactionFee);
                    get_total_pay_amount(parseInt(rent_amount) + parseInt(result.transactionFee));
                }
            }
        });
    }
}

function get_total_pay_amount(total_pay_amount) {
    var total_pay_amount = parseInt(total_pay_amount) + parseInt($("input[name='security_option']:checked").val());
    $(".total_pay_amount").text('$' + (total_pay_amount).toFixed(2));
}

// open menu on mobile
$(document).ready(function () {
    $('.navbar-toggler-icon').click(function () {
        $(".navbar-toggler-icon").toggleClass("active");
        $("body").toggleClass("body-fixed");
    });
});
// open menu on mobile end
// Footer accordion start
$(document).ready(function () {
    // if (window.matchMedia("(max-width:575px)").matches) {
    if (/Android|iPhone/i.test(navigator.userAgent)) {
        // if (screen.width <= 575) {
        $(function () {
            var Accordion = function (el, multiple) {
                this.el = el || {};
                this.multiple = multiple || false;

                // Variables privadas
                var links = this.el.find('.footer-navigation-title');
                // Evento
                links.on('click', { el: this.el, multiple: this.multiple }, this.dropdown)
            }

            Accordion.prototype.dropdown = function (e) {
                var $el = e.data.el;
                $this = $(this),
                    $next = $this.next();

                $next.slideToggle();
                $this.parent().toggleClass('open');

                if (!e.data.multiple) {
                    $el.find('.submenu').not($next).slideUp().parent().removeClass('open');
                };
            }
            var accordion = new Accordion($('#footer-accordion'), false);
        });
    }

});

// function handleValidation(form, rules, messages = {}, submitHandler = false) {
//     if( typeof form == "string" )
//         form = jQuery('form#' + form);
//     let valdiationConfiguration = {
//         errorClass: "invalid-feedback",
//         ignore: [],
//         rules: rules,
//         messages: messages,
//         highlight: function (element) {
//             jQuery(element).siblings("span.invalid-feedback").remove();
//             if (jQuery(element).hasClass('selectric')) {
//                 jQuery(element).parents('.selectric-wrapper').addClass("selectric-is-invalid");
//             } else {
//                 jQuery(element).parent().addClass("is-invalid");
//             }
//             jQuery(element).addClass("is-invalid");
//         },
//         unhighlight: function (element) {
//             if (jQuery(element).hasClass('selectric')) {
//                 jQuery(element).parents('.selectric-wrapper').removeClass("selectric-is-invalid");
//             } else {
//                 jQuery(element).parent().removeClass("is-invalid");
//             }
//             jQuery(element).removeClass("is-invalid");
//         },
//         errorPlacement: function (label, element) {
//             if (jQuery(element).hasClass('selectric')) {
//                 label.removeClass('invalid-feedback').addClass('cstm-selectric-invalid').insertAfter(jQuery(element).parent().siblings('.selectric'))
//             }else if( jQuery(element).hasClass('select2-error') ){
//                 label.insertAfter( $(element).parent() )
//             }else if( jQuery(element).hasClass('form-select') ){
//                 label.insertAfter( $(element).parent().parent() )
//             }else if( jQuery(element).hasClass('form-class') ){
//                 label.insertAfter( $(element).parent() )
//             }else if( jQuery(element).hasClass('star') ){
//                 label.insertAfter( $(element).parent() )
//             }else if( jQuery(element).hasClass('product-images') ){
//                 label.insertAfter( $(element).parent() )
//             }else if( jQuery(element).hasClass('form-category') ){
//                 label.insertAfter( $(element).parent().parent().parent() )  
//             }else if( jQuery(element).hasClass('neighborhood') ){
//                 label.insertAfter( $(element).parent().parent())

//             }else {
//                 label.insertAfter(element)
//             }
//         }
//     };
//     if( submitHandler )
//         valdiationConfiguration.submitHandler = submitHandler;
//     form.validate( valdiationConfiguration );
// }

function handleValidation(form, rules, messages = {}, submitHandler = false) {
    if (typeof form == "string")
        form = jQuery('form#' + form);
    let validationConfiguration = {
        errorClass: "invalid-feedback",
        ignore: [],
        rules: rules,
        messages: messages,
        highlight: function (element) {
            if (jQuery(element).hasClass('selectric')) {
                jQuery(element).parents('.selectric-wrapper').addClass("selectric-is-invalid");
            } else {
                jQuery(element).parent().addClass("is-invalid");
            }
            jQuery(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            if (jQuery(element).hasClass('selectric')) {
                jQuery(element).parents('.selectric-wrapper').removeClass("selectric-is-invalid");
            } else {
                jQuery(element).parent().removeClass("is-invalid");
            }
            jQuery(element).removeClass("is-invalid");
        },
        errorPlacement: function (label, element) {
            let target = element;

            if (jQuery(element).hasClass('selectric')) {
                target = jQuery(element).parent().siblings('.selectric');
                label.removeClass('invalid-feedback').addClass('cstm-selectric-invalid');
            } else if (jQuery(element).hasClass('select2-error') ||
                jQuery(element).hasClass('form-select') ||
                jQuery(element).hasClass('form-class') ||
                jQuery(element).hasClass('star') ||
                jQuery(element).hasClass('product-images')) {
                target = $(element).parent();
            } else if (jQuery(element).hasClass('form-category')) {
                target = $(element).parent().parent().parent();
            } else if (jQuery(element).hasClass('neighborhood')) {
                target = $(element).parent().parent();
            }

            label.insertAfter(target);

            // If it's an email field and there's an existing "email already in use" message, keep it
            if (element.attr('name') === 'email') {
                let existingError = target.siblings('.invalid-feedback[role="alert"]');
                if (existingError.length) {
                    existingError.insertAfter(label);
                }
            }
        }
    };

    if (submitHandler) {

        validationConfiguration.submitHandler = submitHandler;
    }

    form.validate(validationConfiguration);
}
function ajaxCall(url, method, params, loader = true) {
    if (loader) {
        return new Promise((resolve, reject) => {
            let requestObject = {
                url: url,
                method: method,
                data: params,
                // processData: false,
                // contentType: false,
                dataType: 'json',
                beforeSend: function () {
                    jQuery(".submit").attr("disabled", true);
                    jQuery(".show-loader").show();
                    // jQuery(".loader").fadeIn("slow");
                    // jQuery("body").addClass("loading");
                    // jQuery(".loader").addClass("loading");

                },
                complete: function (resp, status) {
                    // jQuery("body").removeClass("loading");
                    jQuery(".submit").attr("disabled", false);
                    jQuery(".show-loader").hide();

                },
                success: function (response) {
                    resolve(response)
                },
                error: function (error) {
                    reject(error)
                }
            };
            if (params instanceof FormData) {
                requestObject.processData = requestObject.contentType = false;
                delete requestObject.dataType;
            }
            // console.log( requestObject );
            jQuery.ajax(requestObject);
        });
    } else {
        return new Promise((resolve, reject) => {
            jQuery.ajax({
                url: url,
                method: method,
                data: params,
                dataType: 'json',
                success: function (response) {
                    resolve(response)
                },
                error: function (error) {
                    reject(error)
                }
            })
        });
    }
}

function addUserAjaxCall(url, method, params, loader = true) {
    if (loader) {
        return new Promise((resolve, reject) => {
            jQuery(document).find(".ajax-response").html('');
            jQuery.ajax({
                url: url,
                method: method,
                data: params,
                dataType: 'json',
                contentType: false,
                processData: false,
                beforeSend: function () {
                    jQuery(".loader").fadeIn("slow");
                    jQuery(".show-loader").show();
                    jQuery(".submit").attr("disabled", true);
                    // jQuery("body").addClass("loading");
                    // jQuery(".loader").addClass("loading");
                },
                complete: function (resp, status) {
                    // jQuery("body").removeClass("loading");
                    jQuery(".loader").fadeOut("slow");
                    jQuery(".show-loader").hide();
                    jQuery(".submit").attr("disabled", false);
                },
                success: function (data) {
                    if (data.success == true) {
                        if (data.url) {
                            window.location.replace(data.url);
                        }
                    } else if (data.success == false) {
                        if ('errortype' in data && data.errortype) {
                            var response_ajax = jQuery(document).find(".ajax-response-" + data.errortype);
                        } else {
                            var response_ajax = jQuery(document).find(".ajax-response");
                            $("html, body").animate({ scrollTop: 0 }, "fast");
                        }
                        response_ajax.html('<div class="alert alert-danger alert-dismissible fade show k" role="alert">' + data.msg + '<button type="button" class="btn-close close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            })
        });
    } else {
        return new Promise((resolve, reject) => {
            jQuery.ajax({
                url: url,
                method: method,
                data: params,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    resolve(response)
                },
                error: function (error) {
                    reject(error)
                }
            })
        });
    }
}

function toggleStatus(element, model, id, field = null, message = null) {
    jQuery(element).attr('disabled', true)
    let params = { 'id': id, 'model': model, 'field': field, 'message': message };
    let url = APP_URL + "/admin/status";
    let response = ajaxCall(url, 'get', params);
    response.then(function (result) {
        // toastr.options.closeButton = true;
        // toastr.options.closeMethod = 'fadeOut';
        // toastr.options.closeDuration = 10;
        jQuery(element).attr('disabled', false)

        if (result.status == 'success') {
            iziToast.success({
                title: "",
                message: result.message,
                position: 'topRight'
            });
            //return toastr.success(result.message);
        }
        else {
            iziToast.error({
                title: "",
                message: result.message,
                position: 'topRight'
            });
            //return toastr.error(result.message);
        }
    }).catch(function (error) {
        jQuery(element).attr('disabled', false)

        return toastr.error(error.responseJSON.message);
    })
}

// common delete function
jQuery(document).ready(function () {
    jQuery(".delete").click(function (e) {
        e.preventDefault();
        jQuery('body').addClass('modal-open');
        let url = jQuery(this).attr('delete_url');
        var getAttr = jQuery(this).attr('data');
        var get_title = 'Are you sure?';
        var get_text = 'You want to delete.';
        if (getAttr && getAttr == 'product') {
            get_title = 'Are you sure you want to delete this product?';
            get_text = 'This cannot be undone.';
        }
        swal({
            title: get_title,
            text: get_text,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
            buttons: ["No", "Yes"],
        })
            .then((willDelete) => {
                if (willDelete) {
                    window.location.replace(url)
                } else {
                    jQuery('body').removeClass('modal-open');
                }
            });
    });
});

// Cancel order
jQuery(".cancel-order").click(function (e) {

    $("#cancel-order").attr('action', $(this).attr("data-url"));

});
$('#cancel-order').submit(function (e) {
    e.preventDefault();
    if ($('#cancel-order').valid()) {
        var formData = new FormData($('form#cancel-order').get(0));
        ajaxCall($("#cancel-order").attr("action"), 'post', formData)
            .then(function (response) {
                if (response) {
                    window.location.replace(response.url);
                }
                else {
                    iziToast.error({
                        title: 'Error',
                        message: 'Something went wrong!',
                        position: 'topRight'
                    });
                }
            },
                function () {
                    // iziToast.error({
                    //     title: 'Error',
                    //     message: 'Something went wrong!',
                    //     position: 'topRight'
                    // });
                }).finally(function () {
                    console.log("error");
                });
    }
});
// End

// Product Review Start
jQuery(".get-review").click(function (e) {
    var orderId = $(this).attr('data-orderId');
    $("#review").attr('action', $(this).attr("data-url"));
    if (orderId) {
        $.ajax({
            url: APP_URL + '/product/review/' + orderId,
            success: function (result) {
                //console.log("result",result);
                if (result.status == true) {
                    $("#reviewProduct").html(result.data.product);
                }
            }
        });
    }
});

jQuery(document).on("click", 'input[name="rating"]', function () {
    $('input[name="rating"]').attr("checked", false);
    $(this).attr("checked", true);
});



$('#review').submit(function (e) {
    e.preventDefault();
    if ($('#review').valid()) {
        var formData = new FormData($('form#review').get(0));
        ajaxCall($("#review").attr("action"), 'post', formData)
            .then(function (response) {
                if (response.success == true) {
                    //console.log(response)
                    window.location.replace(response.url);
                }
                else {
                    iziToast.error({
                        title: 'Review',
                        message: response.msg,
                        position: 'topRight'
                    });
                }
            }, function () {
                iziToast.error({
                    title: 'Error',
                    message: 'Something went wrong!',
                    position: 'topRight'
                });
            }).finally(function () {
                console.log("error");
            });
    }
});
// End


// cookie start
function setCookie(key, value, expiry) {
    var expires = new Date();
    expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
    document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
}
// cookie end

// for search mobile toggle
$(".search-mb").click(function () {
    $("body").toggleClass("search-active")
});

// End

$(document).on("focusout", "input[name='min'],input[name='max']", function () {
    if ($("input[name='min']").val()) {
        if (!$.isNumeric($("input[name='min']").val())) {
            $("input[name='min']").val('');
            return;
        }
        $("input[name='min']").val(parseFloat($("input[name='min']").val()).toFixed(2));
    }

    if ($("input[name='max']").val()) {
        if (!$.isNumeric($("input[name='max']").val())) {
            $("input[name='max']").val('');
            return;
        }
        $("input[name='max']").val(parseFloat($("input[name='max']").val()).toFixed(2));
    }
});

// footer accordion end
// tooltip
//tooltip end
// gallery slider start
// function openModal() {
// 	document.getElementById("myModal").style.display = "block";
//   }

//   function closeModal() {
// 	document.getElementById("myModal").style.display = "none";
//   }

//   var slideIndex = 1;
//   showSlides(slideIndex);

//   function plusSlides(n) {
// 	showSlides(slideIndex += n);
//   }

//   function currentSlide(n) {
// 	showSlides(slideIndex = n);
//   }

//   function showSlides(n) {
// 	var i;
// 	var slides = document.getElementsByClassName("mySlides");
// 	var dots = document.getElementsByClassName("demo");
// 	var captionText = document.getElementById("caption");
// 	if (n > slides.length) {slideIndex = 1}
// 	if (n < 1) {slideIndex = slides.length}
// 	for (i = 0; i < slides.length; i++) {
// 		slides[i].style.display = "none";
// 	}
// 	for (i = 0; i < dots.length; i++) {
// 		dots[i].className = dots[i].className.replace(" active", "");
// 	}
// 	slides[slideIndex-1].style.display = "block";
// 	dots[slideIndex-1].className += " active";
// 	captionText.innerHTML = dots[slideIndex-1].alt;
//   }
// gallery slider end
$('form').on('focus', 'input[type=number]', function (e) {
    $(this).on('wheel.disableScroll', function (e) {
        e.preventDefault()
    })
})
$('form').on('blur', 'input[type=number]', function (e) {
    $(this).off('wheel.disableScroll')
})


function fetchQueries(status, user) {
    // alert("hello");
    $.ajax({
        url: '/fetch-queries',
        type: 'GET',
        data: { status: status, 'user': user },
        beforeSend: function () {
            $('body').addClass('loading');
        },
        success: function (response) {
            if (response.success) {
                $('#query-list-container').html(response.html);
            } else {
                $('#query-list-container').html('<div class="error">Failed to load queries.</div>');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error:', error);
            // $('#query-list-container').html('<div class="error">An error occurred. Please try again.</div>');
        },
        complete: function () {
            $('body').removeClass('loading');
        }
    });
}

$('.tab-item').on('click', function (e) {
    e.preventDefault();

    $('.tab-item').removeClass('active');
    $(this).addClass('active');
    var status = $(this).data('status');
    var user = $(this).data('user');

    fetchQueries(status, user);
});

// for loader

$(document).ready(function() {
    // Show the loader overlay when a link is clicked
      

       $(window).on('beforeunload', function() {
           $('#fullPageLoader').removeClass('d-none'); // Show the loader
       });

       // Hide the loader overlay when the page is fully loaded
       $(window).on('load', function() {
           console.log("hide loader on load");
           $('#fullPageLoader').addClass('d-none'); // Hide the loader
       });
   });

