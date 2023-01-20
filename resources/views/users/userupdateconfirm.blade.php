@extends('layouts.app')

@section('content')

<div class="container text-center bg-info" style="width: 50%;padding:20px">
    <form action="/updateuser/{{$user->id}}" method="post">
        @csrf
        <input type="hidden" name="id" value="{{$user->id}}">
        <input type="hidden" name="name" value="{{$user->name}}">
        <input type="hidden" name="email" value="{{$user->email}}">
        <input type="hidden" name="type" value="{{$user->type}}">
        <input type="hidden" name="phone" value="{{$user->phone}}">
        <input type="hidden" name="profile" value="{{$fname}}">
        <input type="hidden" name="date" value="{{$user->date}}">
        <input type="hidden" name="address" value="{{$user->address}}">
        <div>
            <div class="row my-3">
                <div class="col">Profile</div>
                <div class="col">
                    <img id="preview-image-before-upload" src="{{asset('images/' . $fname)}}" alt="preview image" style="max-height: 100px;">
                </div>
            </div>
            <div class="row my-3">
                <div class="col">Name</div>
                <div class="col">{{$user->name}}</div>
            </div>
            <div class="row my-3">
                <div class="col">Email</div>
                <div class="col">{{$user->email}}</div>
            </div>
            <div class="row my-3">
                <div class="col">Type</div>
                <div class="col">
                    <td>
                        @if($user->type==0)
                        Admin
                        @else
                        User
                        @endif
                    </td>
                </div>
            </div>
            <div class="row my-3">
                <div class="col">Phone</div>
                <div class="col">{{$user->phone}}</div>
            </div>
            <div class="row my-3">
                <div class="col">Date of Birth</div>
                <div class="col">{{$user->date}}</div>
            </div>
            <div class="row my-3">
                <div class="col">Address</div>
                <div class="col">{{$user->address}}</div>
            </div>

            <div class="row my-3">
                <div class="col"><a href="javascript:history.back()">Cancel</a></div>
                <div class="col"><input type="submit" onclick="localStorage.clear();" class="form-control bg-success  fw-bolder text-light" value="Confirm"></div>
            </div>
        </div>
    </form>
</div>
@endsection