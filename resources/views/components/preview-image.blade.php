    @foreach ($images as $index => $image)
        <li>
            <div class="preview-img">
                <img src="{{ $image->url }}" alt="img" />
            </div>
            <div class="img-preview-desc">
                {{-- <p>{{ substr($image->file, 0, $global_preview_file_name_length) }}</p> --}}
                <span class="remove-product" data-index="{{ $index + 1 }}" data-id="{{ $image->id }}">Remove</span>
            </div>
        </li>
    @endforeach
