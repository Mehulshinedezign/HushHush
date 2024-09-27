@extends('layouts.admin')
@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush

@section('content')
    <section>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card cms-card-body">
                        <div class="card-header">
                            <h4>CMS Page</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.savecms', [$page->id]) }}" method="post" id="cmsForm">
                                @csrf
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-2">Name</label>
                                    <div class="col-sm-12 col-md-10">
                                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', @$page->title) }}">
                                        @error('title')
                                            <span class="error-message">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-2">Tag line</label>
                                    <div class="col-sm-12 col-md-10">
                                        <input type="text" name="tag_line" class="form-control @error('tag_line') is-invalid @enderror" value="{{ old('tag_line', @$page->tag_line) }}">
                                        @error('tag_line')
                                            <span class="error-message">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-2">Content</label>
                                    <div class="col-sm-12 col-md-10">
                                        <div id="editor" class="@error('content') is-invalid @enderror">{!! old('content', @$page->content) !!}</div>
                                        <input type="hidden" id="content" name="content" value="{{ old('content', @$page->content) }}">
                                        <span class="invalid-txt" role="alert" style="display:none"></span>
                                        @error('content')
                                            <span class="error-message">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="card-footer text-right mt-5">
                                    <button class="btn btn-primary">Update</button>
                                    <a class="btn btn-dark" href="{{ url()->previous() }}">Back</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script>
        const toolbarOptions = [
            ["bold", "italic", "underline", "strike"],
            ["blockquote", "code-block"],
            [{ list: "ordered" }, { list: "bullet" }],
            [{ script: "sub" }, { script: "super" }],
            [{ indent: "-1" }, { indent: "+1" }],
            [{ direction: "rtl" }],
            [{ size: ["small", false, "large", "huge"] }],
            [{ header: [1, 2, 3, 4, 5, 6, false] }],
            [{ color: [] }, { background: [] }],
            [{ font: [] }],
            [{ align: [] }],
        ];

        const quill = new Quill("#editor", {
            modules: {
                toolbar: toolbarOptions,
            },
            theme: "snow",
        });

        // Set the initial content of the editor
        quill.root.innerHTML = document.querySelector('#content').value;

        const errorSpan = document.querySelector(".invalid-txt");
        let hasError = false;

        quill.on("text-change", validateText);

        function validateText() {
            const len = quill.getLength();
            const text = quill.getText();
            if (text.trim() === "") {
                showError("Content field can't be empty");
                hasError = true;
            } else if (len < 50) {
                showError("Content must be at least 50 characters");
                hasError = true;
            } else if (len > 5000) {
                showError("Content must be at most 5000 characters");
                hasError = true;
            } else {
                hideError();
                hasError = false;
            }
        }

        function showError(message) {
            errorSpan.style.display = "block";
            errorSpan.textContent = message;
        }

        function hideError() {
            errorSpan.style.display = "none";
        }

        const rules = {
            title: {
                required: true,
                minlength: 3,
                maxlength: 100,
            },
            tag_line: {
                required: true,
                minlength: 3,
                maxlength: 100,
            },
        };

        const messages = {
            title: {
                required: "Title is required",
                minlength: "Title must be at least 3 characters",
                maxlength: "Title cannot exceed 100 characters",
            },
            tag_line: {
                required: "Tag line is required",
                minlength: "Tag line must be at least 3 characters",
                maxlength: "Tag line cannot exceed 100 characters",
            },
        };

        function handleValidation(formId, rules, messages = {}) {
            $("#" + formId).validate({
                rules: rules,
                messages: messages,
                errorElement: "span",
                errorClass: "invalid-feedback",
                highlight: function(element) {
                    $(element).addClass("is-invalid");
                },
                unhighlight: function(element) {
                    $(element).removeClass("is-invalid");
                },
            });
        }

        handleValidation("cmsForm", rules, messages);

        document.getElementById('cmsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            validateText();
            if (!hasError && $(this).valid()) {
                document.getElementById("content").value = quill.root.innerHTML;
                this.submit();
            }
        });
    </script>
@endpush
