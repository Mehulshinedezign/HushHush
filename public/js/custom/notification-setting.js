const elements = ['order_placed', 'order_cancelled', 'order_pickup', 'order_return', 'payment', 'welcome_mail', 'feedback', 'user_booking_request', 'lender_accept_booking_request', 'reminder_for_pickup_time_location', 'reminder_for_drop_off_time_location', 'rate_your_experience', 'item_we_think_you_might_like', 'lender_receives_booking_request', 'lender_send_renter_first_msg', 'renter_send_lender_first_msg', 'reminder_to_start_listing_items']
function notification(element) {

    let name = jQuery(element).attr('name');
    if (jQuery.inArray(name, elements) != -1) {
        // check attribute is checked
        let status = jQuery(element).is(":checked") ? 'on' : 'off';
        let data = {
            key: name,
            value: status
        };
        const result = ajaxCall(notify_url, 'post', data);
        result.then(response => {
            iziToast.success({
                title: response.title,
                message: response.message,
                position: 'topRight'
            })
        }).catch(error => {
            iziToast.error({
                title: error.status,
                message: error.statusText,
                position: 'topRight'
            });
        })
    }
}