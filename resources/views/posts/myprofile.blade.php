@extends('layouts.app')

@section('content')
<form action="">
    @csrf
    <div class="container justify-content-center text-center">
        <h1>User Profile</h1>
        <table class="table">
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
                <td>{{$user->type}}</td>
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
        </table>
        <button class="btn btn-info text-white">Edit</button>
    </div>

</form>
@endsection