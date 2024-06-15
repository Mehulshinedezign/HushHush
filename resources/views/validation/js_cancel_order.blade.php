<script>
jQuery(document).ready(function(){
    const rules = {
        cancellation_note:{
            required: true,
            minlength: descMinLength,
            maxlength: descMaxLength,
        },
    }
    const messages = {
        cancellation_note: {
            required: `{{ __('customvalidation.cancelorder.note.required') }}`,
            minlength: `{{ __('customvalidation.cancelorder.note.min', ['min' => '${descMinLength}', 'max' => '${descMaxLength}']) }}`,
            maxlength: `{{ __('customvalidation.cancelorder.note.max', ['min' => '${descMinLength}', 'max' => '${descMaxLength}']) }}`,
        },        
    };    
    handleValidation('cancel-order', rules, messages);
});
</script>
