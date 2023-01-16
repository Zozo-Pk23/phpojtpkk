@extends('layouts.app')

@section('content')

<div class="container bg-info text-center" style="width: 70%;padding:50px;border-radius :25px;">
    <h1>Update Profile</h1>
    <form action="/confirm_profile" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{$user->id}}">
        <input type="hidden" name="oldprofile" value="{{$user->profile}}">
        <table class="table">
            <tr>
                <td>Profile</td>
                <td><img src="{{asset('images/' . $user->profile)}}" style="max-height: 250px;" id="oldprofile" name="oldprofile"></td>
            </tr>
            <tr>
                <td>Name</td>
                <td><input type="text" class="form-control" name="name" id="name" value="{{$user->name}}">
                    @error('name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type="text" class="form-control" name="email" id="email" value="{{$user->email}}">
                    @error('email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>Type</td>
                <td>
                    <select class="form-control" name="type" id="type" value="{{$user->type}}">
                        <option value="0">Admin</option>
                        <option value="1">User</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Phone</td>
                <td><input type="text" class="form-control" name="phone" id="phone" value="{{$user->phone}}">
                    @error('phone')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>Date of Birth</td>
                <td><input type="date" class="form-control" name="date" id="date" value="{{$user->date_of_birth}}"></td>
            </tr>
            <tr>
                <td>Address</td>
                <td><textarea name="address" id="address" class="form-control" name="" id="" cols="30" rows="10">{{$user->address}}</textarea></td>
            </tr>
            <tr>
                <td>Profile</td>
                <td><input type="file" class="form-control" name="profile" id="profile"></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <img id="preview-image-before-upload" src="" alt="preview image" style="max-height: 250px;">
                </td>
            </tr>
            <tr>
                <td><a href="/changepasswordscreen/{{$user->id}}">Change your password</a></td>
            </tr>
            <tr>
                <td><input type="button" class="form-control bg-light fw-bolder text-primary" value="Clear" onclick="document.getElementById('name').value = null;document.getElementById('email').value = null;
                document.getElementById('phone').value = null;
                document.getElementById('date').value = null;
                document.getElementById('address').value = null;
                document.getElementById('profile').value = null;
                
                return false;"></td>
                <td> <input type="submit" class="form-control bg-success  fw-bolder text-light" value="Confirm"></td>
            </tr>
        </table>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function(e) {


        $('#profile').change(function() {

            let reader = new FileReader();

            reader.onload = (e) => {

                $('#preview-image-before-upload').attr('src', e.target.result);
            }

            reader.readAsDataURL(this.files[0]);

        });

    });
</script>
@endsection