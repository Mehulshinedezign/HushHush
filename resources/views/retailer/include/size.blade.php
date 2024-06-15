<select class="form-select getsize" name="size">
    <option value="">Select Size</option>
    @foreach ($sizes as $size)
        <option value="{{ $size->id }}" @if (isset($selectedSize) && $selectedSize == $size->id) selected @endif>{{ $size->name }}
        </option>
    @endforeach
</select>
<input type="text" class="form-control d-none" name="other_size" placeholder="Enter Size"
    value="{{ isset($other_size) ? $other_size : '' }}">
