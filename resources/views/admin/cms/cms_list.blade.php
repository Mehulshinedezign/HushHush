@extends('layouts.admin')

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <x-admin_alert />
                    <div class="card">
                        <div class="card-header">
                            <h4>CMS Pages</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-md">
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Last Modified</th>
                                        <th>Active</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach ($pages as $index => $page)
                                        <tr>
                                            <td>{{ $index + 1 }}</th>
                                            <td>{{ $page->title }}</td>
                                            <td>{{ $page->updated_at ? date('m/d/Y', strtotime($page->updated_at)) : 'N/A' }}
                                            </td>
                                            <td>
                                                <label>
                                                    <input type="checkbox" class="custom-switch-input"
                                                        @if ($page->status == '1') checked="checked" @endif
                                                        onchange="toggleStatus(this, 'CmsPage', '{{ jsencode_userdata($page->id) }}');">
                                                    <span class="custom-switch-indicator"></span>
                                                </label>
                                            </td>
                                            <td>
                                                <a class="btn btn-primary" href="{{ route('admin.editcms', [$page->id]) }}"
                                                    title="View">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
