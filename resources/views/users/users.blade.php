@extends('layouts.app')

@section('content')
<form action="">
    @csrf
    <div class="container justify-content-center text-center">
        <h1>User List</h1>
        <div class="row text-center">
            <div class="col-4"><input type="text" class="form-control"></div>
            <div class="col-2"><input type="date" class="form-control"></div>
            <div class="col-2"><input type="date" class="form-control"></div>
            <div class="col-2">
                <button class="btn btn-info text-white">Search</button>
            </div>
            <div class="col-2">
                <a href="/createuser" class="btn btn-info text-white">Add</a>
            </div>
        </div>
        <table class="table my-3">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Created User</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Birthday</th>
                    <th scope="col">Address</th>
                    <th scope="col">Created at</th>
                    <th scope="col">Updated at</th>
                    <th scope="col">Handle</th>
                </tr>
            </thead>
            <tbody>
                <tr>

                </tr>
            </tbody>
        </table>
    </div>

</form>
@endsection