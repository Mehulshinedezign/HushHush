<link href="{{ asset('/front/css/product.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('front/css/slick.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
    integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.0/css/ion.rangeSlider.min.css">
<link rel="stylesheet" href="{{ asset('front/css/style.css') }}?ver={{ now() }}">
<link rel="stylesheet" href="{{ asset('css/iziToast.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/daterangepicker.css') }}" />
<link rel="stylesheet" href="{{ asset('css/custom-front.css') }}?ver={{ now() }}" />
<link rel="stylesheet" href="{{ asset('front/css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('front/css/responsive.css') }}?ver={{ now() }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<!-- Modal -->
<div class="modal fade product_model" id="NproductModal" tabindex="-1" data-bs-backdrop="static"
    aria-labelledby="NewProductModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="ajax-form-html">
        </div>
    </div>
</div>
@auth
    @include('customer.identification_model')
    @include('customer.bank_details_model')
@endauth
@push('scripts')
    @includeFirst(['validation'])
    @includefirst(['validation.js_product'])
    @includefirst(['layouts.notification-alert'])
    @auth

        <script>
            const commision_type = "{{ adminsetting()->type }}"
            const commision = "{{ adminsetting()->value }}"
            const maxProductImageCount = parseInt("{{ $global_max_product_image_count }}");
            const is_bankdetail = "{{ auth()->user()->vendorBankDetails }}";
            const checkdocuments = "{{ count(auth()->user()->documents) }}";
            const checkdocumentstatus = "{{ auth()->user()->is_approved }}";
            const product_check = "{{ count(auth()->user()->products) }}";
            const image_store_url = "{{ route('image.store') }}";
            var htmlForm = `@include('retailer.include.product-from', ['product' => new App\Models\Product()])`;
        </script>
    @endauth
    <script src="{{ asset('js/custom/product-add-edit.js') }}?ver={{ now() }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        const customDateFormat = 'MM/DD/YYYY';


        jQuery(function() {
            const date = '{{ request()->get('filter_date') }}';
            let start, end;

            if (date) {
                const dateParts = date.split(' - ').map(part => part.trim());
                start = moment(dateParts[0], customDateFormat);
                end = moment(dateParts[1], customDateFormat);
            } else {
                start = moment();
                end = moment();
            }

            jQuery('.daterange-cus').each(function() {
                jQuery(this).daterangepicker({
                    startDate: start,
                    endDate: end,
                    autoUpdateInput: false,
                    locale: {
                        format: customDateFormat,
                        separator: ' - ',
                    },
                });

                if (date) {
                    jQuery(this).val(date);
                }

                jQuery(this).on('apply.daterangepicker', function(ev, picker) {
                    jQuery(this).val(picker.startDate.format(customDateFormat) + ' - ' + picker
                        .endDate.format(customDateFormat));
                    if (jQuery(this).closest('form').attr('id') === 'searchForm') {
                        jQuery(this).closest('form').submit();
                    }
                });

                jQuery(this).on('cancel.daterangepicker', function(ev, picker) {
                    jQuery(this).val('');
                });
            });
        });
    </script>

    
@endpush
