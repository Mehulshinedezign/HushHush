@extends('layouts.admin')

@section('content')
    <div class="section-body">
		<div class="row">
			<div class="col-12 col-md-12 col-lg-12">

                <div class="card">
                    <form action="{{ route('admin.updatesetting', [$setting->id]) }}" method="post" id="editSetting">
                        <div class="card-header">
                            <h4>Edit Setting</h4>
                        </div>
                        @csrf
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>{{ ucwords(str_replace('_',' ',$setting->key)) }}</label>
                                    @if('global_product_pagination' == $setting->key)
                                        <input type="text" name="product_pagination" class="form-control" value="{{ old('product_pagination', $setting->value) }}">
                                        @error('product_pagination')
                                            <label class="error-messages">{{ $message }}</label>
                                        @enderror
                                    @elseif ('global_home_page_title' == $setting->key)
                                        <input type="text" name="home_page_title" class="form-control" value="{{ old('home_page_title', $setting->value) }}">
                                        @error('home_page_title')
                                            <label class="error-messages">{{ $message }}</label>
                                        @enderror
                                    @elseif ('global_footer_page_content' == $setting->key)
                                        <input type="text" name="footer_page_title" class="form-control" value="{{ old('footer_page_title', $setting->value) }}">
                                        @error('footer_page_title')
                                            <label class="error-messages">{{ $message }}</label>
                                        @enderror                                    
                                    @else
                                        <select name="value" class="form-control">
                                            @foreach(config('settings.'.$setting->key) as $option => $value)
                                                <option value="{{ $option }}" {{ old('value', $setting->value) == $option ? 'selected' : '' }}> {{ $value }} @if(!in_array($setting->key, ['global_pagination', 'global_date_separator'])) - {{ date($value) }} @endif</option>
                                            @endforeach
                                        </select>
                                        @error('value')
                                            <label class="error-messages">{{ $message }}</label>
                                        @enderror
                                    @endif

                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary" type="submit">{{ __('buttons.update') }}</button>
                            <a class="btn btn-dark" href="{{ route('admin.settings') }}">{{ __('buttons.back') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        jQuery(document).ready(function() {
            jQuery('#editSetting').validate({
                rules: {
                    value: {
                        required: true
                    },
                    home_page_title: {
                        maxlength: 250
                    },
                    footer_page_title: {
                        maxlength: 250
                    }
                }
            });
        })
    </script>
@stop
