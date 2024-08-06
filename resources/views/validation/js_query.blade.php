<script>
jQuery(document).ready(function(){
    const rules = {
        rental_dates:{
            required: true,
        },
        description:{
            required: true,
            minlength: descMinLength,
            maxlength: descMaxLength,
        },
    }
    const messages = {
        rental_dates: {
            required:'Please Select proper date range',
        },
        description: {
            required: 'Please enter description',
            minlength: 'Please describe the description',
            maxlength: 'Please use less than 1000 words to describe description',
        },        
    };    
    handleValidation('Sendquery', rules, messages);
});
</script>