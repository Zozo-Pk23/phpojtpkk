@extends('layouts.app')

@section('content')
<form action="/edit_profile/{{$user->id}}" method="get" enctype="multipart/form-data">
    @csrf
    <div class="container justify-content-center text-center">
        <h1>User Profile</h1>
        <!-- <input type="hidden" name="id" value="{{$user->id}}">
        <input type="hidden" name="name" value="{{$user->name}}">
        <input type="hidden" name="email" value="{{$user->email}}">
        <input type="hidden" name="type" value="{{$user->type}}">
        <input type="hidden" name="phone" value="{{$user->phone}}">
        <input type="hidden" name="profile" value="{{$user->profile}}">
        <input type="hidden" name="date_of_birth" value="{{$user->date_of_birth}}">
        <input type="hidden" name="address" value="{{$user->address}}"> -->
        <table class="table" style="width: 60%;margin:0 auto;">
            <tr>
                <td>Name</td>
                <td>{{$user->name}}</td>
            </tr>
            <tr>
                <td>Email address</td>
                <td>{{$user->email}}</td>
            </tr>
            <tr>
                <td>Type</td>
                <td>
                    @if($user->type==0)
                    Admin
                    @else
                    User
                    @endif
                </td>
            </tr>
            <tr>
                <td>Phone</td>
                <td>{{$user->phone}}</td>
            </tr>
            <tr>
                <td>Date of Birth</td>
                <td>{{$user->date_of_birth}}</td>
            </tr>
            <tr>
                <td>Address</td>
                <td>{{$user->address}}</td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button class="btn btn-warning text-white">Edit</button>
                </td>
            </tr>
        </table>
    </div>

</form>
@endsection