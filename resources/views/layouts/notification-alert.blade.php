@if (session()->has('success'))
<script>
    var msg = "{{ session()->get('success') }}";
    swal(msg, "", "success")
</script>
@endif

@if (session()->has('warning'))
<script>
    var msg = "{{ session()->get('warning') }}";
    swal(msg, "You clicked the button!", "warning")
</script>
@endif

@if (session()->has('error'))
<script>
    var msg = "{{ session()->get('error') }}";
    swal(msg, "You clicked the button!", "error")
</script>
@endif