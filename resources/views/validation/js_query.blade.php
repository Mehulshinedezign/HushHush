<script>
jQuery(document).ready(function(){
    const rules = {
        rental_dates:{
            required: true,
        },
        description:{
            required: true,

        },
        // delivery_option:{
        //     required: true,
        // }
    }
    const messages = {
        rental_dates: {
            required:'Please select proper date range',
        },
        description: {
            required: 'Please enter description',

        },
        // delivery_option:{
        //     required: 'Please select location ',
        // }
    };
    handleValidation('Sendquery', rules, messages);
});
</script>
