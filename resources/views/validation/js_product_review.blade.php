<script>
jQuery(document).ready(function(){
    const rules = {
        rating:{
            required: true,
        },
        review:{
            required: true,
            minlength: descMinLength,
            maxlength: descMaxLength,
        },
    }
    const messages = {
        rating: {
            required: `{{ __('customvalidation.productReview.rating.required') }}`,
        },
        review: {
            required: `{{ __('customvalidation.productReview.review.required') }}`,
            minlength: `{{ __('customvalidation.productReview.review.min', ['min' => '${descMinLength}', 'max' => '${descMaxLength}']) }}`,
            maxlength: `{{ __('customvalidation.productReview.review.max', ['min' => '${descMinLength}', 'max' => '${descMaxLength}']) }}`,
        },        
    };    
    handleValidation('review', rules, messages);
});
</script>
