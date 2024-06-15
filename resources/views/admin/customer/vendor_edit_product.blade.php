s @extends('layouts.admin')
 @section('content')

 <div class="section-body">
     <div class="row">
         <div class="col-12 col-md-12 col-lg-12">
             <div class="card">
                 <form action="{{route('admin.update.product',['product'=> $product->id])}}" method="post" id="addCategory" enctype="multipart/form-data">
                     <div class="card-header">
                         <!-- <h4>{{ __('category.addCategory') }}</h4> -->
                     </div>
                     @csrf
                     <div class="card-body">
                         <div class="form-row">
                             <div class="form-group col-md-4">
                                 <label> Name <span class="text-danger">*</span></label>
                                 <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $product->name }}" placeholder="{{ __('category.placeholders.name') }}" disabled>
                                 @error('name')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                                 @enderror
                             </div>

                             <!-- <div class="form-row"> -->
                             <div class="form-group col-md-4">
                                 <label> Quantity <span class="text-danger">*</span></label>
                                 <input type="text" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ $product->quantity }}" placeholder="Enter Product Quantity" disabled>
                                 @error('quantity')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                                 @enderror
                             </div>

                             <!-- <div class="form-row"> -->
                             <div class="form-group col-md-4">
                                 <label>Size</label>
                                 <select name="size" class="form-control @error('size') is-invalid @enderror" disabled>
                                     <option selected>{{ $product->size }}</option>
                                     <option value="Small">Small </option>
                                     <option value="Medium">Medium</option>
                                     <option value="Large">Large</option>
                                     <option value="Extra Large">Extra Large</option>
                                    
                                    
                                 </select>
                                 @error('size')
                                 <label class="error-messages">{{ $message }}</label>
                                 @enderror
                                 </select>
                             </div>

                             <!-- <div class="form-row"> -->
                             <div class="form-group col-md-4">
                                 <label> Rent <span class="text-danger">*</span></label>
                                 <input type="text" name="rent" class="form-control @error('rent') is-invalid @enderror" placeholder="Enter rent" value="{{ $product->rent }}" disabled>
                                 @error('rent')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                                 @enderror
                             </div>

                             <!-- <div class="form-row"> -->
                             <div class="form-group col-md-4">
                                 <label> Price <span class="text-danger">*</span></label>
                                 <input type="text" name="price" class="form-control @error('price') is-invalid @enderror" placeholder="Enter Price" value="{{ $product->price }}" disabled>
                                 @error('price')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                                 @enderror
                             </div>

                            <div class="form-group col-md-4">
                                 <label>Status</label>
                                 <select name="status" class="form-control @error('size') is-invalid @enderror">
                                     <option value="{{ $product->status==1 ? 1:0  }}">{{ $product->status==1?"Active":"Inactive" }}</option>
                                     <option value="1">Active </option>
                                     <option value="0">Inactive</option>
                                  
                                 </select>
                                 @error('size')
                                 <label class="error-messages">{{ $message }}</label>
                                 @enderror
                                 </select>
                             </div>
            
                             <div class="card-footer">
                                 <button class="btn btn-primary" type="submit">{{ __('buttons.save') }}</button>
                             </div>
                         </div>
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>
 @endsection

 {{-- @push('scripts')
 <script src="{{ asset('js/custom/profile.js') }}"></script>
 
@endpush --}}