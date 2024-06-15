@extends('layouts.admin')

@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                    <form action="{{ route('admin.savecms', [$page->id]) }}" method="post" id="cmsForm">
                        @csrf
                        <div class="card-header">
                            <h4>CMS Page</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-wrapper">
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label>Title</label>
                                        <input type="text" name="title" class="form-control"
                                            value="{{ old('title', @$page->title) }}" placeholder="Title">

                                        @error('title')
                                            <label class="error-messages">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label>Tagline</label>
                                        <input type="text" name="tag_line" class="form-control"
                                            value="{{ old('tag_line', @$page->tag_line) }}" placeholder="Tagline">

                                        @error('tag_line')
                                            <label class="error-messages">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>
                                {{-- @if (@$page->id == 2)
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Display text</label>
                                            <input type="text" name="display_text" class="form-control"
                                                value="{{ old('display_text', @$page->display_text) }}"
                                                placeholder="display text">

                                            @error('tag_line')
                                                <label class="error-messages">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Url</label>
                                            <input type="text" name="url" class="form-control"
                                                value="{{ old('url', @$page->url) }}" placeholder="url">

                                            @error('tag_line')
                                                <label class="error-messages">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                @endif --}}
                                @if (@$page->id == 8)
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Address</label>
                                            <input type="text" name="contact[address]" class="form-control"
                                                value="{{ old('address', isset($page->content) ? json_decode($page->content, true)['address'] : '') }}"
                                                placeholder="address">

                                            @error('tag_line')
                                                <label class="error-messages">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Email</label>
                                            <input type="text" name="contact[email]" class="form-control"
                                                value="{{ old('email', isset($page->content) ? json_decode($page->content, true)['email'] : '') }}"
                                                placeholder="email">

                                            @error('tag_line')
                                                <label class="error-messages">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Phone</label>
                                            <input type="text" name="contact[phone]" class="form-control"
                                                value="{{ old('phone', isset($page->content) ? json_decode($page->content, true)['phone'] : '') }}"
                                                placeholder="phone">

                                            @error('tag_line')
                                                <label class="error-messages">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Instagram</label>
                                    <input type="text" name="contact[instagram]" class="form-control" value="{{ old('instagram',json_decode($page->content, true)['instagram']) ?? ''}}" placeholder="instagram">

                                    @error('tag_line')
                                        <label class="error-messages">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Facebook</label>
                                    <input type="text" name="contact[facebook]" class="form-control" value="{{ old('facebook',json_decode($page->content, true)['facebook']) ?? ''}}" placeholder="facebook">

                                    @error('tag_line')
                                        <label class="error-messages">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Linkedin</label>
                                    <input type="text" name="contact[linkedin]" class="form-control" value="{{ old('twitter',json_decode($page->content, true)['linkedin']) ?? ''}}" placeholder="twitter">

                                    @error('tag_line')
                                        <label class="error-messages">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>              
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Twitter</label>
                                    <input type="text" name="contact[twitter]" class="form-control" value="{{ old('linkdin',json_decode($page->content, true)['twitter']) ?? ''}}" placeholder="linkdin">

                                    @error('tag_line')
                                        <label class="error-messages">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div> --}}
                                @endif
                                @if ($page->id != 8)
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label>Content</label>
                                            <textarea name="content">{{ old('content', @$page->content) }}</textarea>
                                            @error('content')
                                                <label class="error-messages">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                                <div class="d-flex">
                                    <button class="btn btn-primary mr-2" type="submit">{{ __('buttons.update') }}</button>
                                    <a class="btn btn-dark" href="{{ url()->previous() }}">{{ __('buttons.back') }}</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="https://cdn.tiny.cloud/1/sf647xm7nb5lys09h59i8ldg44m5rnocd9zu99xxd9z60e7r/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        const url = '{{ route('admin.uploadcms', @$page->id) }}'
        tinymce.init({
            selector: 'textarea',
            width: '100%',
            height: '640',
            branding: false,
            plugins: 'link image code',
            toolbar: "insertfile undo redo | styleselect | bold | italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code",
            image_title: true,
            // relative_urls: true,
            images_upload_url: url,
            automatic_uploads: true,
            file_picker_types: 'image',
            setup: function(editor) {
                editor.on('init change', function() {
                    editor.save();
                });
            },
            file_picker_callback: function(cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.onchange = function() {
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.onload = function() {
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(
                            blobInfo
                        ); /* call the callback and populate the Title field with the file name */
                        cb(blobInfo.blobUri(), {
                            title: file.name
                        });
                    };
                    reader.readAsDataURL(file);
                };
                input.click();
            },
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
            height: 500
        });

        jQuery(document).ready(function() {
            var validator = jQuery("form#cmsForm").submit(function() {
                // update underlying textarea before submit validation
                let content = jQuery.trim(jQuery('#content_ifr').contents().find('body').text());
                jQuery('#content_ifr').contents().find('body').text(content);
                tinyMCE.triggerSave();
            }).validate({
                errorClass: "error-messages",
                ignore: "",
                rules: {
                    title: {
                        required: true,
                    },
                    // tag_line: {
                    //     required: true,
                    // },
                    // content: {
                    //     required: true,
                    // }
                },
                messages: {
                    title: {
                        required: 'Please enter the title',
                    },
                    tag_line: {
                        required: 'Please enter the tag line',
                    },
                    content: {
                        required: 'Please enter the content',
                    }
                },
                errorPlacement: function(label, element) {
                    if (element.is("textarea")) {
                        label.insertAfter(element.next());
                    } else {
                        label.insertAfter(element)
                    }
                }
            });
        })
    </script>
@endpush
