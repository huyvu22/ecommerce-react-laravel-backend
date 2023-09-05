@extends('admin.layouts.master')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>FAQs</h1>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create FAQs</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.faqs.store')}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="">Title</label>
                                    <input type="text" name="title" class="form-control" value="{{old('title')}}">
                                    @if($errors->has('title'))
                                        <span class="text-danger">{{ $errors->first('title') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Content</label>
                                    <textarea class="form-control" name="content" id="" cols="6">{{old('content')}}</textarea>
                                    @if($errors->has('content'))
                                        <span class="text-danger">{{ $errors->first('content') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Status</label>
                                 <select class="form-control" name="status">
                                     <option value="1" {{old('status')==1?? 'selected'}}>Active</option>
                                     <option value="0" {{old('status')==0?? 'selected'}}>Inactive</option>
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

