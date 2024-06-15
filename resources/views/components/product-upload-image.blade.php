<div class="m_wrapper formfield">
    <div class="">
        <h6 class="upload-font-txt"> <small class=" smallFont w-400 grey-text"> </small></h6>
        <div class="product-pic-gallery gallery-upload">
            <div class="multi-file-upload">
                {{-- @for ($i = 1; $i <= $global_max_product_image_count; $i++)
                    
                @endfor --}}
                <input type="file" name="image[]" class="product-images upload-pending" multiple
                    accept="image/png,image/jpeg,image/jpg" productId="{{ $product->id }}" />
                <span><img src="{{ asset('img/upload-multi.svg') }}" alt="upload-multi"></span>
                <p class="medFont m-0">
                    <span class="limit-reached-text">Upload Photos</span>
                    <span class="d-block smallFont">(Min upload: {{ $global_min_product_image_count }}, Max
                        upload: {{ $global_max_product_image_count }}, Max file size:
                        {{ $global_php_file_size / 1000 }}MB)
                    </span>
                    <span class="d-block smallFont">(Allowed File
                        Extensions: JPG, JPEG, PNG)</span>
                    <span class="uploaded-file-name"></span>
                </p>
            </div>
            <label class="custom-error size-error"></label>
            <label class="custom-error extension-error"></label>
            <input type="hidden" name="removed_images" id="removedImages">
            <input type="hidden" name="uploaded_image" id="uploadedImage">
            <input type="hidden" name="uploaded_image_count" id="uploadedImageCount"
                value="{{ $product->allImages->count() }}">
            {{-- @for ($i = 1; $i <= $global_max_product_image_count; $i++)
                @if ($errors->has('image.' . $i))
                    <label class="error-messages">{{ $errors->first('image.' . $i) }}</label>
                @endif
            @endfor --}}

            @error('error')
                <label class="error-messages">{{ $message }}</label>
            @enderror
            @error('thumbnail_image')
                <label class="error-messages">{{ $message }}</label>
            @enderror

            <ul class="product-img-preview">
                @foreach ($product->allImages as $index => $image)
                    <li>
                        <div class="preview-img">
                            <img src="{{ $image->url }}" alt="img" />
                        </div>
                        <div class="img-preview-desc">
                            <p>{{ substr($image->file, 0, $global_preview_file_name_length) }}</p>
                            <span class="remove-product" data-index="{{ $index + 1 }}"
                                data-id="{{ $image->id }}">Remove</span>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

    </div>
</div>
