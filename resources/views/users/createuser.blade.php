@extends('layouts.app')

@section('content')

<div class="container bg-info text-center" style="width: 70%;padding:50px;border-radius :25px;">
    <form action="/confirmuser" method="post">
        @csrf
        <table class="table">
            <tr>
                <td>Name</td>
                <td><input type="text" class="form-control" name="name" id="name"></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type="text" class="form-control" name="email" id="email"></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="text" class="form-control" name="password" id="password"></td>
            </tr>
            <tr>
                <td>Confirm Password</td>
                <td><input type="text" class="form-control" name="confirmpassword" id="confirmpassword"></td>
            </tr>
            <tr>
                <td>Type</td>
                <td>
                    <div class="dropdown" class="form-control">
                        <button class="btn bg-white dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            User
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="#">Admin</a></li>
                            <li><a class="dropdown-item" href="#">User</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Phone</td>
                <td><input type="text" class="form-control" name="phone" id="phone"></td>
            </tr>
            <tr>
                <td>Date of Birth</td>
                <td><input type="date" class="form-control" name="date" id="date"></td>
            </tr>
            <tr>
                <td>Address</td>
                <td><textarea name="address" id="address" class="form-control" name="" id="" cols="30" rows="10"></textarea></td>
            </tr>
            <tr>
                <td>Profile</td>
                <td><input type="file" class="form-control" name="profile" id="profile"></td>
            </tr>
            <tr>
                <td><input type="submit" class="form-control bg-light fw-bolder text-primary" value="Clear" onclick="document.getElementById('title').value = null;document.getElementById('des').value = null; return false;"></td>
                <td> <input type="submit" class="form-control bg-success  fw-bolder text-light" value="Confirm"></td>
            </tr>
        </table>
    </form>
</div>


@endsection