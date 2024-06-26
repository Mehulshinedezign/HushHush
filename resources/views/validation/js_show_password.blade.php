<script>
    jQuery(document).ready(function() {
        // jQuery('.toggle-password').click(function() {
        //     jQuery(this).find('i').removeClass('fa-eye fa-eye-slash');
        //     var element = jQuery(this).siblings('input');
        //     var attr = element.attr("type") == "text" ? "password" : "text";
        //     var className = (attr == 'password') ? 'fa-eye' : 'fa-eye-slash';
        //     jQuery(this).find('i').addClass(className);
        //     jQuery(element).attr("type", attr);
        // });
        $('.togglePassword').click(function() {
            var $input = $(this).closest('.formfield').find('input');
            $(this).toggleClass('fa-lock fa-unlock');
            $input.prop('type', function(index, oldType) {
                return oldType === 'password' ? 'text' : 'password';
            });
        })
    });
</script>
