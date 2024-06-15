@props([
    'statusField' => false,
    'nameField' => true,
    'dateField' => true,
    'categoryTypeField' => false,
    'SearchVia' => false,
    'ordersField' => false,
    'Export' => false,
    'payouts' => false,
    'securitypayout' => false,
    'disputed' => false,
    'brandField' => false,
])
<div class="card-header-form mb-4">
    <form id="listing-search">
        <div class="form-row justify-content-end ">

            @if ($ordersField)
                <div class="input-group col-md-2">
                    <input autocomplete="off" class="form-control" name="order" type="text" placeholder="Order ID"
                        value="{{ @request()->order }}">
                </div>

                <div class="input-group col-md-2">
                    <input autocomplete="off" class="form-control" name="customer" type="text"
                        placeholder="Renter Email" value="{{ @request()->customer }}">
                </div>

                <div class="input-group col-md-2">
                    <input autocomplete="off" class="form-control" name="retailer" type="text"
                        placeholder="Lender Email" value="{{ @request()->retailer }}">
                </div>

                <div class="input-group col-md-2">
                    <input autocomplete="off" class="form-control" name="transaction" type="text"
                        placeholder="Payment ID" value="{{ @request()->transaction }}">
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-control selectric" tabindex="-1">
                        <option value="all" @if (request()->get('status') == 'all') selected @endif>Filter</option>
                        <option value="cancelled" @if (request()->get('status') == 'cancelled') selected @endif>Cancelled</option>
                        <option value="completed" @if (request()->get('status') == 'completed') selected @endif>Completed</option>
                        <option value="waiting" @if (request()->get('status') == 'Pending') selected @endif>Pending</option>
                        <option value="picked up" @if (request()->get('status') == 'picked up') selected @endif>Picked Up</option>
                    </select>
                </div>
            @endif

            @if ($nameField)
                <div class="input-group col-md-2">
                    <input type="text" autocomplete="off" class="form-control"
                        placeholder="@if ($SearchVia) Search via email @else Search @endif"
                        name="search" value="{{ request()->search }}">
                </div>
            @endif

            @if ($statusField)
                {{-- dropdown --}}
                <div class="col-md-2">
                    <select name="filter_by_status" class="form-control selectric" tabindex="-1">
                        <option value="">Filter by</option>
                        <option {{ request()->filter_by_status == 'Active' ? 'selected' : '' }} value="Active">Active
                        </option>
                        <option value="Inactive" {{ request()->filter_by_status == 'Inactive' ? 'selected' : '' }}>
                            Inactive</option>
                        @if ($categoryTypeField)
                            <option {{ request()->filter_by_status == 'main' ? 'selected' : '' }} value="main">Main
                            </option>
                            <option value="sub" {{ request()->filter_by_status == 'sub' ? 'selected' : '' }}>Sub
                                category</option>
                        @endif
                    </select>
                </div>
            @endif

            @if ($brandField)
            <div class="col-md-2">
                <select name="filter_by_status" class="form-control selectric" tabindex="-1">
                    <option value="">Filter by</option>
                    <option {{ request()->filter_by_status == 'Active' ? 'selected' : '' }} value="Active">Active
                    </option>
                    <option value="Inactive" {{ request()->filter_by_status == 'Inactive' ? 'selected' : '' }}>
                        Inactive</option>
                </select>
            </div>
            @endif

            @if ($dateField)
                <div class="input-group col-md-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text append-icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                    </div>
                    <input type="text" autocomplete="off" class="form-control daterange-cus pre-icon" name="dates">
                </div>
            @endif

            <!-- Additional search filters -->
            {{ $slot }}

            <div class="input-group col-md-2" style="flex-basis:content">
                <div class="input-group-btn">
                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                </div>
            </div>
            @if (request()->route()->getName() != 'admin.user.list' &&
                    request()->route()->getName() != 'admin.creator.list' &&
                    request()->route()->getName() != 'admin.payments.listing')
                <div class="input-group col-md-2" style="flex-basis:content">
                    <div class="input-group-btn">
                        <a href="{{ route(Route::currentRouteName(), request()->id) }}" class="btn btn-primary">Clear
                            Filter</a>
                    </div>
                </div>
            @endif
            @if ($Export && request()->route()->getName() == 'admin.customers.export')
                <div class="input-group col-md-2" style="flex-basis:content">
                    <div class="input-group-btn">
                        <a href="{{ route('admin.customers.export') }}" class="btn btn-dark">
                            Export Data
                        </a>
                    </div>
                </div>
            @endif
            @if ($Export)
                <div class="input-group col-md-2" style="flex-basis:content">
                    <div class="input-group-btn">
                        <a @if ($Export == true) href="{{ route('admin.payouts_export.export') }}"
                     @elseif($Export == true)href="{{ route('admin.payouts_export.export', ['security' => 'securitypayout']) }}"
                      @elseif($Export == true)href="{{ route('admin.payouts_export.export', ['dispute' => 'disputepayout']) }}" @endif
                            class="btn btn-primary">
                            Export Data
                        </a>
                    </div>
                </div>
            @endif
            {{ $last_element ?? '' }}

        </div>
    </form>
</div>

@push('scripts')
    <script>
        jQuery(document).ready(function() {
            jQuery('input[name="dates"]').daterangepicker({
                locale: {
                    format: "YYYY-MM-DD",
                    autoApply: true,
                    cancelLabel: 'Clear'
                }
            });
            jQuery('input[name="dates"]').val('');
            jQuery('input[name="dates"]').attr("placeholder", "Select Dates");
            jQuery('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {});

        });
    </script>
@endpush
