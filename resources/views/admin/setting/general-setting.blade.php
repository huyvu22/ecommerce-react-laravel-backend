<div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
    <div class="card border">
        <div class="card-body">
            <form action="{{route('admin.general-setting')}}" method="post">
                @csrf
                <div class="form-group">
                    <label for="">Site Name</label>
                    <input type="text" name="name" class="form-control" value="{{$generalSetting->site_name}}">
                    @if($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>
{{--                <div class="form-group">--}}
{{--                    <label for="">Layout</label>--}}
{{--                    <select name="layout" id="" class="form-control">--}}
{{--                        <option value="LTR">LTR</option>--}}
{{--                        <option value="RTL">RTL</option>--}}
{{--                    </select>--}}
{{--                </div>--}}
                <div class="form-group">
                    <label for="">Contact Email</label>
                    <input type="text" name="email" class="form-control" value="{{$generalSetting->email}}">
                    @if($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="">Contact Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{$generalSetting->phone}}">
                    @if($errors->has('phone'))
                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="">Contact Address</label>
                    <input type="text" name="address" class="form-control" value="{{$generalSetting->address}}">
                    @if($errors->has('address'))
                        <span class="text-danger">{{ $errors->first('address') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="">Map</label>
                    <input type="text" name="map" class="form-control" value="{{$generalSetting->map}}">
                    @if($errors->has('map'))
                        <span class="text-danger">{{ $errors->first('map') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="">Default Currency Name</label>
                    <select name="currency_name" id="" class="form-control">
                        <option value="usd" selected="{{$generalSetting->currency_name === 'usd'}}">USD</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Currency Icon</label>
                    <input type="text" name="currency_icon" class="form-control" value="{{$generalSetting->currency_icon}}">
                    @if($errors->has('currency_icon'))
                        <span class="text-danger">{{ $errors->first('currency_icon') }}</span>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
