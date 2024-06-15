<div class="filter-popup">
    <!-- Modal -->
    <div class="modal fade" id="home-filter" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('index') }}" method="get">
            <div class="modal-content">
                <div class="home-filter-wrapper">
                    <div class="home-filter-box">
                        <div class="filter-item-box">
                            <h6>Category</h6>
                            <ul>
                                @foreach(getParentCategory() as $index => $category)
                                <li>
                                    <div class="checkbox-filter-field">
                                        <label for="category{{$category->id}}">{{$category->name}}</label>
                                        <input type="checkbox" class="check" id="category{{$category->id}}" name="category[{{ $index }}]" value="{{$category->id}}" @if(isset($filters) && in_array($category->id, $filters['categories'])) checked @endif>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="filter-content-box">
                            <div class="item-color-box">
                                <h6>Color</h6>
                                <ul class="list-unstyled filter-listing color_wrapper">
                                    @foreach(getColors() as $key => $color)
                                    <li class="filter-name-list-item color_varint">
                                        <input id="filteroption-color-{{ $key }}" class="colorRadioinput" type="checkbox" name="filtercolor[{{ $key }}]" value="{{ $color->id }}" @if(isset($filters) && in_array($color->id, $filters['selectedcolor'])) checked @endif>
                                        <label for="filteroption-color-{{ $key }}" class="colorMainbox" data-tooltip="{{ $color->name }}" style="background-color: {{ $color->color_code }}">
                                            <span class="hidden-text">{{ $color->name }}</span>
                                        </label>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="filter-condition-box">
                                <h6>Condition</h6>
                                <ul class="condition-checkbox">
                                    <li>
                                        <div class="checkbox-condition-field">
                                            <input type="checkbox" class="check" id="filter-item-10" name="condition[0]" value="Excellent" @if(isset($filters) && in_array('Excellent', $filters['selectedcondition'])) checked @endif>
                                            <label for="filter-item-10">Excellent</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="checkbox-condition-field">
                                            <input type="checkbox" class="check" id="filter-item-12" name="condition[1]" value="Good" @if(isset($filters) && in_array('Good', $filters['selectedcondition'])) checked @endif>
                                            <label for="filter-item-12">Good</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="checkbox-condition-field">
                                            <input type="checkbox" class="check" id="filter-item-13" name="condition[2]" value="Bad" @if(isset($filters) && in_array('Bad', $filters['selectedcondition'])) checked @endif>
                                            <label for="filter-item-13">Fine</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="filter-content-box size-box">
                            <div class="filter-range-box">
                                <h6>Price Range</h6>
                                <div class="item-range-input-box">
                                    <div class="formfield">
                                        <input type="text" class="form-control" name="min" placeholder="min" value="{{ request()->get('min') }}">
                                        <span class="doller-icon">
                                            $
                                        </span>
                                    </div>
                                    <div class="formfield">
                                        <input type="text" class="form-control" name="max" placeholder="max" value="{{ request()->get('max') }}">
                                        <span class="doller-icon">
                                            $
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="item-size-box">
                                <h6>Size</h6>
                                <div class="item-different-sizes"> 
                                    @foreach(get_size_by_type('type1') as $k => $size)
                                        <div class="different-sizes-box d-grid">
                                                <label>
                                                    <input type="checkbox" name="size[{{$size->id}}]" value="{{$size->id}}" @if(isset($filters) && in_array($size->id, $filters['selectedsize'])) checked @endif> 
                                                    <span>{{ $size->name }}</span>
                                                </label>
                                        </div>
                                    @endforeach                                    
                                </div><br>
                                <div class="item-different-sizes">
                                    @foreach(get_size_by_type('type2') as $k => $size)
                                        <div class="different-sizes-box d-grid">
                                                <label>
                                                    <input type="checkbox" name="size[{{$size->id}}]" value="{{$size->id}}" @if(isset($filters) && in_array($size->id, $filters['selectedsize'])) checked @endif> 
                                                    <span>{{ $size->name }}</span>
                                                </label>
                                        </div> 
                                    @endforeach                                    
                                </div><br>
                                <div class="item-different-sizes">
                                    @foreach(get_size_by_type('type3') as $k => $size)
                                    <div class="different-sizes-box d-grid">
                                                <label>
                                                    <input type="checkbox" name="size[{{$size->id}}]" value="{{$size->id}}" @if(isset($filters) && in_array($size->id, $filters['selectedsize'])) checked @endif> 
                                                    <span>{{ $size->name }}</span>
                                                </label>
                                        </div> 
                                    @endforeach                                    
                                </div><br>
                                <div class="item-different-sizes">
                                    @foreach(get_size_by_type('type5') as $k => $size)
                                    <div class="different-sizes-box d-grid">
                                                <label>
                                                    <input type="checkbox" name="size[{{$size->id}}]" value="{{$size->id}}" @if(isset($filters) && in_array($size->id, $filters['selectedsize'])) checked @endif> 
                                                    <span>{{ $size->name }}</span>
                                                </label>
                                        </div> 
                                    @endforeach                                    
                                </div><br>
                                <div class="item-different-sizes">
                                    @foreach(get_size_by_type('type6') as $k => $size)
                                    <div class="different-sizes-box d-grid">
                                                <label>
                                                    <input type="checkbox" name="size[{{$size->id}}]" value="{{$size->id}}" @if(isset($filters) && in_array($size->id, $filters['selectedsize'])) checked @endif> 
                                                    <span>{{ $size->name }}</span>
                                                </label>
                                        </div> 
                                    @endforeach                                    
                                </div>
                            </div>
                        </div>
                        <div class="filter-item-box">
                            <h6>Brand</h6>
                            <form>
                                <ul>
                                    @foreach(getBrands() as $k => $brand)
                                    <li>
                                        <div class="checkbox-filter-field">
                                            <label for="brand{{$brand->id}}">{{$brand->name}}</label>
                                            <input type="checkbox" class="check" id="brand{{$brand->id}}" name="brand[{{$k}}]" value="{{$brand->id}}" @if(isset($filters) && in_array($brand->id, $filters['selectedbrands'])) checked @endif>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn primary-btn">Apply Filter</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>