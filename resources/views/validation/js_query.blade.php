<script>
jQuery(document).ready(function(){
    const rules = {
        rental_dates:{
            required: true,
        },
        description:{
            required: true,
          
        },
    }
    const messages = {
        rental_dates: {
            required:'Please Select proper date range',
        },
        description: {
            required: 'Please enter description',
            
        },        
    };    
    handleValidation('Sendquery', rules, messages);
});
</script>