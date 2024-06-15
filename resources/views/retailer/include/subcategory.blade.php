<select class="form-select sub_category" name="sub_cat">
    <option value="">Sub category</option>
    @foreach($sub_categories as $subcat)
    <option value="{{ jsencode_userdata($subcat->id) }}" @if(isset($subcatId) && $subcatId == $subcat->id) selected @endif>{{ $subcat->name }}</option>
    @endforeach
</select>