{{--@extends('frontend.layouts.master')--}}
{{--@section('title')--}}
{{--    Shop Now--}}
{{--@endsection--}}
{{--@section('content')--}}
{{--    <!--============================--}}
{{--         BREADCRUMB START--}}
{{--    ==============================-->--}}
{{--    <section id="wsus__breadcrumb">--}}
{{--        <div class="wsus_breadcrumb_overlay">--}}
{{--            <div class="container">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-12">--}}
{{--                        <h4>login / register</h4>--}}
{{--                        <ul>--}}
{{--                            <li><a href="{{route('home')}}">home</a></li>--}}
{{--                            <li><a href="#">login / register</a></li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}
{{--    <!--============================--}}
{{--        BREADCRUMB END--}}
{{--    ==============================-->--}}


{{--    <!--============================--}}
{{--       LOGIN/REGISTER PAGE START--}}
{{--    ==============================-->--}}
{{--    <section id="wsus__login_register">--}}
{{--        <div class="container">--}}
{{--            <div class="row">--}}
{{--                <div class="col-xl-5 m-auto">--}}
{{--                    <div class="wsus__login_reg_area">--}}
{{--                        <ul class="nav nav-pills mb-3" id="pills-tab2" role="tablist">--}}
{{--                            <li class="nav-item" role="presentation">--}}
{{--                                <button class="nav-link active" id="pills-home-tab2" data-bs-toggle="pill"--}}
{{--                                    data-bs-target="#pills-homes" type="button" role="tab" aria-controls="pills-homes"--}}
{{--                                    aria-selected="true">login</button>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item" role="presentation">--}}
{{--                                <button class="nav-link" id="pills-profile-tab2" data-bs-toggle="pill"--}}
{{--                                    data-bs-target="#pills-profiles" type="button" role="tab"--}}
{{--                                    aria-controls="pills-profiles" aria-selected="true">signup</button>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                        <div class="tab-content" id="pills-tabContent2">--}}
{{--                            <div class="tab-pane fade show active" id="pills-homes" role="tabpanel"--}}
{{--                                aria-labelledby="pills-home-tab2">--}}
{{--                                <div class="wsus__login">--}}
{{--                                    <form method="post" action="{{route('login')}}">--}}
{{--                                        @csrf--}}

{{--                                        <div class="wsus__login_input">--}}
{{--                                            <i class="fas fa-user-tie"></i>--}}
{{--                                            <input type="text" id="email" name="email" value="{{old('email')}}" placeholder="Email">--}}
{{--                                        </div>--}}
{{--                                        @if($errors->has('email'))--}}
{{--                                            <span class="text-danger d-inline-block ms-5 ps-3">{{ $errors->first('email') }}</span>--}}
{{--                                        @endif--}}
{{--                                        <div class="wsus__login_input">--}}
{{--                                            <i class="fas fa-key"></i>--}}
{{--                                            <input type="password" id="password" name="password" placeholder="Password">--}}
{{--                                        </div>--}}
{{--                                        @if($errors->has('password'))--}}
{{--                                            <span class="text-danger d-inline-block ms-5 ps-3">{{ $errors->first('password') }}</span>--}}
{{--                                        @endif--}}

{{--                                        <div class="wsus__login_save">--}}
{{--                                            <div class="form-check form-switch">--}}
{{--                                                <input class="form-check-input" type="checkbox" name="remember"--}}
{{--                                                    id="flexSwitchCheckDefault">--}}
{{--                                                <label class="form-check-label" for="flexSwitchCheckDefault">--}}
{{--                                                    Remember me</label>--}}
{{--                                            </div>--}}
{{--                                            <a class="forget_p" href="{{route('password.request')}}">forget password ?</a>--}}
{{--                                        </div>--}}
{{--                                        <button class="common_btn" type="submit">login</button>--}}
{{--                                    </form>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="tab-pane fade" id="pills-profiles" role="tabpanel"--}}
{{--                                aria-labelledby="pills-profile-tab2">--}}
{{--                                <div class="wsus__login">--}}
{{--                                    <form method="post" action="{{route('register')}}">--}}
{{--                                        @csrf--}}
{{--                                        <div class="wsus__login_input">--}}
{{--                                            <i class="fas fa-user-tie"></i>--}}
{{--                                            <input type="text" id="name" placeholder="Name" name="name" value="{{old('name')}}">--}}
{{--                                        </div>--}}
{{--                                        @if($errors->has('name'))--}}
{{--                                            <span class="text-danger d-inline-block ms-5 ps-3">{{ $errors->first('name') }}</span>--}}
{{--                                        @endif--}}
{{--                                        <div class="wsus__login_input">--}}
{{--                                            <i class="far fa-envelope"></i>--}}
{{--                                            <input type="email" id="email" placeholder="Email" name="email" value="{{old('email')}}">--}}
{{--                                        </div>--}}
{{--                                        @if($errors->has('email'))--}}
{{--                                            <span class="text-danger d-inline-block ms-5 ps-3">{{ $errors->first('email') }}</span>--}}
{{--                                        @endif--}}
{{--                                        <div class="wsus__login_input">--}}
{{--                                            <i class="fas fa-key"></i>--}}
{{--                                            <input type="password" id="password" placeholder="Password" name="password">--}}
{{--                                        </div>--}}
{{--                                        @if($errors->has('password'))--}}
{{--                                            <span class="text-danger d-inline-block ms-5 ps-3">{{ $errors->first('password') }}</span>--}}
{{--                                        @endif--}}
{{--                                        <div class="wsus__login_input">--}}
{{--                                            <i class="fas fa-key"></i>--}}
{{--                                            <input type="password"  name="password_confirmation" placeholder="Confirm Password">--}}
{{--                                        </div>--}}
{{--                                        @if($errors->has('password_confirmation'))--}}
{{--                                            <span class="text-danger d-inline-block ms-5 ps-3">{{ $errors->first('password_confirmation') }}</span>--}}
{{--                                        @endif--}}
{{--                                        <div class="wsus__login_save">--}}
{{--                                            <div class="form-check form-switch">--}}
{{--                                                <input class="form-check-input" type="checkbox"--}}
{{--                                                    id="flexSwitchCheckDefault03">--}}
{{--                                                <label class="form-check-label" for="flexSwitchCheckDefault03">I consent--}}
{{--                                                    to the privacy policy</label>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <button class="common_btn" type="submit">signup</button>--}}
{{--                                    </form>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}
{{--    <!--============================--}}
{{--       LOGIN/REGISTER PAGE END--}}
{{--    ==============================-->--}}
{{--@endsection--}}
