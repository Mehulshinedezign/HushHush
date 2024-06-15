<link href="{{ asset('/front/css/product.css') }}" rel="stylesheet" />
<!-- Modal -->
<div class="modal fade product_model" id="NproductModal" tabindex="-1" data-bs-backdrop="static"
    aria-labelledby="NewProductModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="ajax-form-html">
        </div>
    </div>
</div>
@include('customer.identification_model')
@include('customer.bank_details_model')

@push('scripts')
    @includeFirst(['validation'])
    @includefirst(['validation.js_product'])
    @includefirst(['layouts.notification-alert'])
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
    <script src="{{ asset('js/custom/product-add-edit.js') }}?ver={{ now() }}"></script>
@endpush
