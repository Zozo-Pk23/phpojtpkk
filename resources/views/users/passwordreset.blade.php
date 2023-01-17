@extends('layouts.app')
@section('content')
<form action="/updatepassword/{{$password->id}}" method="POST" class="form-control">
    @csrf
    <div class="container" style="width: 40%;">
        <h1>Change Passsword</h1>
        <div class="row">
            @if($errors->any())
            <h4 class="text-danger">{{$errors->first()}}</h4>
            @endif
            <div class="col">
                <p>Old Password :</p>
            </div>
            <div class="col">
                <input type="password" class="form-control" name="oldpassword" id="oldpassword">
                <!-- @error('oldpassword')
                <div class="text-danger">{{ $message }}</div>
                @enderror -->
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>New Password :</p>
            </div>
            <div class="col">
                <input type="password" class="form-control" name="newpassword" id="newpassword">
                <!-- @error('newpassword')
                <div class="text-danger">{{ $message }}</div>
                @enderror -->
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>Confirm Password :</p>
            </div>
            <div class="col">
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                <!-- @error('password_confirmation')
                <div class="text-danger">{{ $message }}</div>
                @enderror -->
            </div>
        </div>
        <div class="row">
            <div class="col">
                <button class="form-control btn-white" onclick="document.getElementById('oldpassword').value=null;
                document.getElementById('newpassword').value=null;
                document.getElementById('password_confirmation').value=null;" type="reset">Clear</button>
            </div>
            <div class="col">
                <input class="form-control btn-info" type="submit" value="Change Password">
            </div>
        </div>
    </div>
</form>
@endsection