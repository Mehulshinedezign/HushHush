<script>
    jQuery(document).ready(function() {
        const rules = {
            images: {
                required: true,
            },
        };
        const messages = {
            images: {
                required: 'This field is required',
            },
        };

        handleValidation('disputeOrder', rules, messages);

        $('#disputeOrder').submit(function(e) {
            e.preventDefault(); // Prevent form submission

            // Perform form validation
            // Check if the form is valid
            if ($('#disputeOrder').valid()) {
                $('form#disputeOrder').submit();
            }
        });
    });
</script>
