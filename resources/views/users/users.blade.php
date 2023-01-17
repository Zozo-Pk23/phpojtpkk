@extends('layouts.app')

@section('content')
<div class="container">
    @csrf
    <div class="container justify-content-center text-center">
        <h1>User List</h1>
        <div class="row text-center">
            <div class="col-10">
                <form action="/searchuser" method="post" class="row">
                    @csrf
                    <div class="col-2"><input type="text" class="form-control" name="searchname" id="searchname" placeholder="Name"></div>
                    <div class="col-2"><input type="text" class="form-control" name="searchemail" id="searchemail" placeholder="Email"></div>
                    <div class="col-3"><input type="date" class="form-control" name="createdfrom" id="createdfrom"></div>
                    <div class="col-3"><input type="date" class="form-control" name="createdto" id="createdto"></div>
                    <div class="col-2">
                        <button class="btn btn-info text-white">Search</button>
                    </div>
                </form>
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
                @foreach($users as $user)
                <tr>
                    <td>
                        <a data-bs-target="#exampleModal{{$user->id}}" data-bs-toggle="modal" class="MainNavText" id="MainNavHelp" href="#myModal">{{$user->name}}</a>
                    </td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->pname}}</td>
                    <td>{{$user->phone}}</td>
                    <td>{{date('Y/m/d', strtotime($user->date_of_birth))}}</td>
                    <td>{{$user->address}}</td>
                    <td>{{$user->created_at}}</td>
                    <td>{{$user->updated_at}}</td>
                    <td><button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#Delete{{$user->id}}">Delete</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center">
        {!! $users->links() !!}
    </div>
    @foreach($users as $user)
    <div class="modal" tabindex="-1" id="Delete{{$user->id}}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure that you would like to Delete?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form id="delete-form" method="POST" action="/deleteuser/{{$user->id}}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}

                        <div class="form-group">
                            <button class="btn-danger btn">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@foreach($users as $user)
<div class="modal fade" id="exampleModal{{$user->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form action="">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <table class="table">
                        <tr>
                            <td>
                                Profile
                            </td>
                            <td>
                                <img src="{{asset('images/' . $user->profile)}}" style="max-height: 100px;" id="oldprofile" name="oldprofile">
                            </td>
                        </tr>
                        <tr>
                            <td>Name</td>
                            <td>
                                {{$user->name}}
                            </td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>
                                {{$user->email}}
                            </td>
                        </tr>
                        <tr>
                            <td>Birthday</td>
                            <td>
                                {{$user->date_of_birth}}
                            </td>
                        </tr>
                        <tr>
                            <td>Phone</td>
                            <td>
                                {{$user->phone}}
                            </td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>
                                {{$user->address}}
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endforeach
@endsection