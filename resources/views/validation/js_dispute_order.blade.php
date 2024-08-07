<script>
    jQuery(document).ready(function() {
        const rule = {
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
        const message = {
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

        handleValidation('disputeForm', rule, message);

        $('#disputeForm').submit(function(e) {
            e.preventDefault(); // Prevent form submission

            // Perform form validation
            // Check if the form is valid
            if ($('#disputeForm').valid()) {
                $('form#disputeForm').submit();
            }
        });
    });
</script>
