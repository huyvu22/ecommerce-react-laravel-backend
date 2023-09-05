@extends('admin.layouts.master')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Product</h1>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create Product</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.products.store')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="">Image</label>
                                    <input type="file" name="image" class="form-control" value="{{ old('image') }}">
                                    @if($errors->has('image'))
                                        <span class="text-danger">{{ $errors->first('image') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input type="text" name="name" class="form-control" value="{{old('name')}}">
                                    @if($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Category</label>
                                            <select class="form-control category" name="category">
                                                <option value="" >Select</option>
                                                @foreach($categories as $category)
                                                    <option value="{{$category->id}}" {{ old('category') == $category->id ? 'selected' : '' }}>{{$category->name}}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('category'))
                                                <span class="text-danger">{{ $errors->first('category') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Sub Category</label>
                                            <select class="form-control sub_category" name="sub_category">
                                                <option value="" >Select</option>
                                            </select>
                                            @if($errors->has('sub_category'))
                                                <span class="text-danger">{{ $errors->first('sub_category') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="">SKU</label>
                                    <input type="text" name="sku" class="form-control" value="{{old('sku')}}">
                                    @if($errors->has('sku'))
                                        <span class="text-danger">{{ $errors->first('sku') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="">Price</label>
                                    <input type="text" name="price" class="form-control" value="{{old('price')}}">
                                    @if($errors->has('price'))
                                        <span class="text-danger">{{ $errors->first('price') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="">Offer Price</label>
                                    <input type="text" name="offer_price" class="form-control" value="{{old('offer_price')}}">
                                    @if($errors->has('offer_price'))
                                        <span class="text-danger">{{ $errors->first('offer_price') }}</span>
                                    @endif
                                </div>


                                <div class="form-group">
                                    <label for="">Stock Quantity</label>
                                    <input type="number" name="stock_quantity" min="0" class="form-control" value="{{old('stock_quantity')}}">
                                    @if($errors->has('stock_quantity'))
                                        <span class="text-danger">{{ $errors->first('stock_quantity') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="">Short Description</label>
                                    <textarea name="short_description" id="" class="form-control" >{{ old('short_description') }}</textarea>
                                    @if($errors->has('short_description'))
                                        <span class="text-danger">{{ $errors->first('short_description') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="">Full Description</label>
                                    <textarea name="full_description" id="" class="form-control summernote">{{ old('full_description') }}</textarea>
                                    @if($errors->has('full_description'))
                                        <span class="text-danger">{{ $errors -> first('full_description') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="">Product Type</label>
                                    <select class="form-control" name="product_type">
                                        <option value="">Select</option>
                                        <option value="new_arrival" {{ old('product_type') == 'new_arrival' ? 'selected' : '' }}>New Arrival</option>
                                        <option value="featured" {{ old('product_type') == 'featured' ? 'selected' : '' }}>Featured</option>
                                        <option value="best_product" {{ old('product_type') == 'best_product' ? 'selected' : '' }}>Best Product</option>
                                    </select>
                                    @if($errors->has('is_top'))
                                        <span class="text-danger">{{ $errors->first('is_top') }}</span>
                                    @endif
                                </div>


                                <div class="form-group">
                                    <label for="">Seo Title</label>
                                    <input type="text" name="seo_title" class="form-control" value="{{old('seo_title')}}">
                                    @if($errors->has('seo_title'))
                                        <span class="text-danger">{{ $errors->first('seo_title') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="">Seo Description</label>
                                    <textarea name="seo_description" id="" class="form-control summernote">{{ old('seo_description') }}</textarea>
                                    @if($errors->has('seo_description'))
                                        <span class="text-danger">{{ $errors->first('seo_description') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="">Status</label>
                                    <select class="form-control" name="status">
                                        <option value="" {{old('status') == '' ? 'selected' : ''}}>Select</option>
                                        <option value="1" {{old('status') == 1 ? 'selected' : ''}}>Active</option>
                                        <option value="0" {{old('status') === 0 ? 'selected' : ''}}>Inactive</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">Create</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

