<script>
jQuery(document).ready(function(){
    const rules = {
        rental_start_date:{
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
        rental_start_date: {
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
