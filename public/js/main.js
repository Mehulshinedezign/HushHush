
//================== window-scroll-position-fix-js
jQuery(window).scroll(function () {
  if (jQuery(this).scrollTop() > 50) {
    jQuery('.mobile-nav-toggle').addClass('mobile-toggle-top');
  }
  else {
    jQuery('.mobile-nav-toggle').removeClass('mobile-toggle-top');
  }
});

//================== window-scroll-position-fix-js-end


//menu-js-start=======================

!(function ($) {
  "use strict";

  // Smooth scroll for the navigation menu and links with .scrollto classes
  var scrolltoOffset = $('#header').outerHeight() - 1;
  $(document).on('click', '.nav-menu a, .mobile-nav a, .scrollto', function (e) {
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
      e.preventDefault();
      var target = $(this.hash);
      if (target.length) {

        var scrollto = target.offset().top - scrolltoOffset;

        if ($(this).attr("href") == '#header') {
          scrollto = 0;
        }

        $('html, body').animate({
          scrollTop: scrollto
        }, 1500, 'easeInOutExpo');

        if ($(this).parents('.nav-menu, .mobile-nav').length) {
          $('.nav-menu .active, .mobile-nav .active').removeClass('active');
          $(this).closest('li').addClass('active');
        }

        if ($('body').hasClass('mobile-nav-active')) {
          $('body').removeClass('mobile-nav-active');
          $('.mobile-nav-toggle i').toggleClass('icofont-navigation-menu icofont-close');
          $('.mobile-nav-overly').fadeOut();
        }
        return false;
      }
    }
  });



  // Mobile Navigation
  if ($('.nav-menu').length) {
    var $mobile_nav = $('.nav-menu').clone().prop({
      class: 'mobile-nav d-lg-none'
    });
    $('body').append($mobile_nav);
    $('body').prepend('<button type="button" class="mobile-nav-toggle d-lg-none"><span class="bar1"></span><span class="bar2"></span><span class="bar3"></span></button>');
    $('body').append('<div class="mobile-nav-overly"></div>');

    $(document).on('click', '.mobile-nav-toggle', function (e) {
      $('body').toggleClass('mobile-nav-active');
      $('.mobile-nav-toggle i').toggleClass('icofont-navigation-menu icofont-close');

      $('.mobile-nav-overly').toggle();
    });

    $(document).on('click', '.mobile-nav .drop-down > a', function (e) {
      e.preventDefault();
      $(this).next().slideToggle(300);
      $(this).parent().toggleClass('active');
    });

    $(document).click(function (e) {
      var container = $(".mobile-nav, .mobile-nav-toggle");
      if (!container.is(e.target) && container.has(e.target).length === 0) {
        if ($('body').hasClass('mobile-nav-active')) {
          $('body').removeClass('mobile-nav-active');
          $('.mobile-nav-toggle').toggleClass('icofont-navigation-menu icofont-close');
          $('.mobile-nav-overly').fadeOut();
        }
      }
    });
  }
  else if ($(".mobile-nav, .mobile-nav-toggle").length) {
    $(".mobile-nav, .mobile-nav-toggle").hide();
  }

  //menu-js-end=======================






  // Init AOS
  function aos_init() {
    AOS.init({
      duration: 800,
      easing: "ease-in-out",
      once: true
    });
  }
  $(window).on('load', function () {
    aos_init();
  });

})(jQuery);



// ====custom-select-box-js

$(".selectBox").on("click", function (e) {
  $(this).toggleClass("show");
  var dropdownItem = e.target;
  var container = $(this).find(".selectBox__value");
  container.text(dropdownItem.text);
  $(dropdownItem)
    .addClass("active")
    .siblings()
    .removeClass("active");
});



// ===============floating-form-label-input-js====================
$('.formfield input, .formfield textarea').on('focus blur', function (e) {
  $(this).parents('.formfield').toggleClass('is-focused', (e.type === 'focus' || this.value.length > 0));
}).trigger('blur');


//=========================uploading-photo-js
// $(document).on("click", ".browse", function () {
//   var file = $(this).parents().find(".file");
//   file.trigger("click");
// });
// $('input[type="file"]').change(function (e) {
//   var fileName = e.target.files[0].name;
//   $("#file").val(fileName);

//   var reader = new FileReader();
//   reader.onload = function (e) {
//     document.getElementById("preview").src = e.target.result;
//   };
//   reader.readAsDataURL(this.files[0]);
// });


//====================== product-slider-js
// trendingEvents-slider

jQuery(document).ready(function ($) {
  jQuery('#product-slider').owlCarousel({
    loop: true,
    center: false,
    items: 4,
    margin: 30,
    autoplay: true,
    dots: false,
    nav: true,
    autoplayTimeout: 8500,
    smartSpeed: 450,
    responsive: {
      0: {
        items: 1
      },
      768: {
        items: 2
      },
      1170: {
        items: 3
      },
      1600: {
        items: 4
      }
    }
  });
});


// =============collection-slider-js
jQuery(document).ready(function () {

  jQuery("#trendingEvents-slider").owlCarousel({

    autoPlay: 3000,
    margin: 16,
    items: 4,
    nav: true,
    dots: false,
    responsive: {
      0: {
        items: 1
      },
      768: {
        items: 2
      },
      1170: {
        items: 3
      },
      1600: {
        items: 4
      }
    }

  });

});

// Active sidbar for mobile
$(".toggle-menu").click(function(){
  $("body").toggleClass("sidebar-active") 
});      


// ==========datepicker-js

// $('#datepicker').datepicker({
//   weekStart: 1,
//   daysOfWeekHighlighted: "6,0",
//   autoclose: true,
//   todayHighlight: true,
// });
// $('#datepicker').datepicker("setDate", new Date());