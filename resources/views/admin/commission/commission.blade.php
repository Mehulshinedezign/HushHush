@extends('layouts.admin')
@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <x-admin_alert />
                <div class="card author-box">
                    <div class="card-header">
                        <h4>Commission</h4>
                    </div>
                    <form action="{{ route('admin.commission') }}" method="POST" id="commission">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                @foreach ($adminSettings as $adminSetting)
                                    <div class="form-group col-md-6 col-12">
                                        <label>{{ ucwords(str_replace('_', ' ', $adminSetting->key)) }} Type</label>
                                        <div class="input-group">
                                            <select name="{{ $adminSetting->key }}_type" class="form-control">
                                                <option value="Percentage"
                                                    @if ($adminSetting->type == 'Percentage') selected @endif>Percentage</option>
                                                <option value="Fixed" @if ($adminSetting->type == 'Fixed') selected @endif>
                                                    Fixed</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6 col-12">
                                        <label>{{ ucwords(str_replace('_', ' ', $adminSetting->key)) }}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="{{ $adminSetting->key }}"
                                                value="{{ $adminSetting->value }}" maxlength="10"
                                                onkeyup="this.value=this.value.replace(/[^0-9]/g,'');">
                                            @error($adminSetting->key)
                                                <label class="error-messages">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button id="userSave" class="btn btn-primary">{{ __('buttons.saveChanges') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
