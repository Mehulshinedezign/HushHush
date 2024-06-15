@extends('layouts.admin')

@section('content')
    <div class="section-body">
		<div class="row">
			<div class="col-12 col-md-12 col-lg-12">

                <div class="card">
                    <form method="post" id="viewCategory">
                        <div class="card-header">
                            <h4>{{ __('category.viewCategory') }}</h4>
                        </div>
                        @csrf
                        <div class="card-body">

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>{{ __('category.fields.name') }}</label>
                                    <input type="text" name="name" class="form-control" value="{{ $category->name }}" disabled>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>{{ __('category.fields.status') }}</label>
                                    <div class="input-group">
                                        <input type="checkbox" name="status" value="Active" @if($category->status == 'Active') checked @endif disabled>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer">
                            <a class="btn btn-primary" href="{{ route('admin.categories') }}">{{ __('buttons.back') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection