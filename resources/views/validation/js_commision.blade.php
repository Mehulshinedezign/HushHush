<script>
    jQuery(document).ready(function() {
        rules = {
            order_commission_type: {
                required: true,
            },
            order_commission: {
                required: true,
                decimal: true,
            }
        }
        const messages = {

            order_commission_type: {
                required: `This field is required.`,
            },
            order_commission: {
                required: `This field is required.`,
                decimal: "Only numbers!",
            },

        };
        handleValidation('commission', rules, messages);
    });
</script>
