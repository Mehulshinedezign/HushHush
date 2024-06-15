@extends('layouts.admin')

@section('content')

    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <x-admin_alert/>
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('setting.title') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-md">
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('setting.name') }}</th>
                                        <th>{{ __('setting.value') }}</th>
                                        <th>{{ __('common.updatedAt') }}</th>
                                        <th>{{ __('common.action') }}</th>
                                    </tr>

                                    @foreach($settings as $index => $setting)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $setting->key ? ucwords(str_replace(['_', 'global'],[' ', ''],$setting->key)) : 'N/A' }}</td>
                                            <td>{{ $setting->value ? $setting->value : 'N/A' }}</td>
                                            <td>{{ $setting->updated_at ? date($global_date_format, strtotime($setting->updated_at)) : 'N/A' }}</td>
                                            <td>
                                                <a title="{{ __('setting.editTitle') }}" href="{{route('admin.editsetting',[$setting->id])}}" class="btn btn-success"><i class="fas fa-edit info"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if ($settings->count() == 0)
                                        <tr>
                                            <td colspan="5" class="text-center text-danger">{{ __('setting.empty') }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
