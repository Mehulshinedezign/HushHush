<select class="form-select get_size" name="type">
    <option value="">Select Type</option>
    @foreach($types as $type)
    <option value="{{ jsencode_userdata($type->size_type) }}" @if((isset($selectedType) && $selectedType) || (isset($selectedType_Other) && $selectedType_Other) == $type->size_type) selected @endif>{{ ucfirst($type->size_type) }}</option>
    @endforeach
</select>