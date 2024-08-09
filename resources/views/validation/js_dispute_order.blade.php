<script>
    jQuery(document).ready(function() {
        const rules = {
            subject: {
                required: true,
            },
            description: {
                required: true,
            },
            'images[]': {
                required: true,
            },
        };
        const messages = {
            subject: {
                required: 'This field is required',
            },
            description: {
                required: 'This field is required',
            },
            'images[]': {
                required: 'This field is required',
            },
        };

        handleValidation('disputeForm', rules, messages);

        jQuery('#dispute').find('button').click(function(e) {
            e.preventDefault(); // Prevent form submission
            if ($('#disputeForm').valid()) {
                $('form#disputeForm').submit();
            }
        })
        // $('#disputeForm').submit(function(e) {
        //     e.preventDefault(); // Prevent form submission
        //     // Check if the form is valid


        //     if ($('#disputeForm').valid()) {
        //         $('form#disputeForm').submit();
        //     }
        // });
    });
</script>
