// product filters
jQuery(document).ready(function () {
    jQuery("#filterForm").submit(function (e) {
        if (jQuery('#neighborhoodcity').val() == '' && jQuery('#reservation_date').val() == '') {
            return e.preventDefault();
        }

        jQuery(this).find(":input").filter(function () {
            return !this.value;
        }).attr("disabled", "disabled");

        return true; // ensure form still submits
    });
});

var currentUrl = new URL(window.location.href);

// search product
jQuery('form#productSearchForm').submit(function (e) {
    e.preventDefault();
    let product = jQuery('#search').val();
    if (product != '') {
        currentUrl.searchParams.set("product", product);
    } else {
        currentUrl.searchParams.delete("product");
    }
    currentUrl.searchParams.delete("page");
    window.location.replace(currentUrl.href)
});

// category filter
jQuery('.product-category').click(function () {
    jQuery('.product-category:checkbox').each(function (i) {
        if (jQuery(this).prop("checked") === true) {
            currentUrl.searchParams.set("category[" + i + "]", jQuery(this).val());
        } else {
            currentUrl.searchParams.delete("category[" + i + "]");
        }
    })
    currentUrl.searchParams.delete("page");
    window.location.replace(currentUrl.href)
});

// Day/Hour filter
jQuery('.product-rental-type').click(function () {
    let dayHour = jQuery(this).val();
    if (jQuery(this).prop("checked") === true) {
        currentUrl.searchParams.set("rental_type", dayHour);
    }
    if (dayHour == 'day') {
        jQuery('#hour').prop('checked', false);
    } else {
        jQuery('#day').prop('checked', false);
    }

    // check the checkbox is null or not 
    if (!$("#day").is(":checked") && !$("#hour").is(":checked")) {
        currentUrl.searchParams.delete("rental_type");
    }

    currentUrl.searchParams.delete("page");
    window.location.replace(currentUrl.href)
});

// price filter
// jQuery('.range-slider').change(function () {
//     let rent = jQuery(this).find('input').val()
//     currentUrl.searchParams.delete("page");
//     currentUrl.searchParams.set("rent", rent);
//     window.location.replace(currentUrl.href)
// });

// rating filter
jQuery('.filter-product-rating').click(function () {
    currentUrl.searchParams.delete("rating");
    if (jQuery(this).prop('checked') == true) {
        currentUrl.searchParams.set("rating", jQuery(this).val());
    }
    jQuery(".filter-product-rating").prop('checked', false);
    currentUrl.searchParams.delete("page");
    window.location.replace(currentUrl.href)
});

// sorting filter
jQuery('select[name="sort_by"]').change(function () {
    let sortBy = jQuery(this).val()
    currentUrl.searchParams.delete("page");
    if (sortBy != '') {
        currentUrl.searchParams.set("sort_by", sortBy);
    } else {
        currentUrl.searchParams.delete("sort_by");
    }
    window.location.replace(currentUrl.href)
});

// First let's set the colors of our sliders
const settings = {
    fill: "#1372E6",
    background: "#fff"
};

// First find all our sliders
const sliders = document.querySelectorAll(".range-slider");
Array.prototype.forEach.call(sliders, (slider) => {
    slider.querySelector("input").addEventListener("input", (event) => {
        // 1. apply our value to the span
        slider.querySelector("span").innerHTML = parseFloat(event.target.value).toFixed(2);
        // 2. apply our fill to the input
        applyFill(event.target);
    });
    // Don't wait for the listener, apply it now!
    applyFill(slider.querySelector("input"));
});

// This function applies the fill to our sliders by using a linear gradient background
function applyFill(slider) {
    // Let's turn our value into a percentage to figure out how far it is in between the min and max of our input
    const percentage =
        (100 * (slider.value - slider.min)) / (slider.max - slider.min);
    // now we'll create a linear gradient that separates at the above point
    // Our background color will change here
    const bg = `linear-gradient(90deg, ${settings.fill} ${percentage}%, ${settings.background} ${percentage + 0.1}%)`;

    slider.style.background = bg;
}