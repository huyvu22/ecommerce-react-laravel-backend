@extends('frontend.layouts.master')
@section('content')
    <section id="wsus__login_register">
        <div class="container">
            <div class="row">
                <div class="col-xl-5 col-md-10 col-lg-7 m-auto">
                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf

                        <div class="wsus__change_password">
                            <h4>Reset password</h4>

                            <input type="hidden" name="token" value="{{ $request->route('token') }}">
                            <div class="wsus__single_pass">
                                <label>email</label>
                                <input type="email" name="email" value="{{old('email', $request->email)}}" >
                            </div>
                            <div class="wsus__single_pass">
                                <label>password</label>
                                <input type="password" id="password" name="password" placeholder="New Password">
                                @if($errors->has('password'))
                                    <code>{{$errors->first('password')}}</code>
                                @endif
                            </div>

                            <div class="wsus__single_pass">
                                <label>confirm password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
                                @if($errors->has('password_confirmation'))
                                    <code>{{$errors->first('password_confirmation')}}</code>
                                @endif
                            </div>
                            <button class="common_btn" type="submit">submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
