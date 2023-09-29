@extends('admin.layouts.master')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Privacy Policy</h1>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Privacy Policy</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.policy.update')}}" method="post">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <label for="">Content</label>
                                    <textarea id="" name="content" class="summernote">{!! @$content->content !!}</textarea>
                                    @if($errors->has('content'))
                                        <span class="text-danger">{{ $errors->first('content') }}</span>
                                    @endif
                                </div>

                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection



