@extends('layouts.app')

@section('content')

<div class="container bg-info text-center" style="width: 70%;padding:50px;border-radius :25px;">
    <h1>Create User</h1>
    <form action="/confirmuser" method="post" enctype="multipart/form-data">
        @csrf
        <table class="table">
            <tr>
                <td>Name</td>
                <td><input type="text" class="form-control" name="name" id="name">
                    @error('name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type="text" class="form-control" name="email" id="email">
                    @error('email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="text" class="form-control" name="password" id="password">
                    @error('password')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>Confirm Password</td>
                <td><input type="text" class="form-control" name="confirmpassword" id="confirmpassword">
                    @error('confirmpassword')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </td>
            </tr>

            <tr>
                <td>Type</td>
                <td>
                    <select class="form-control" name="type" id="type">
                        <option value="0">Admin</option>
                        <option value="1">User</option>
                    </select>
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
                <td></td>
                <td>
                    <img id="preview-image-before-upload" src="https://www.riobeauty.co.uk/images/product_image_not_found.gif" alt="preview image" style="max-height: 250px;">
                </td>
            </tr>
            <tr>
                <td><input type="submit" class="form-control bg-light fw-bolder text-primary" value="Clear" onclick="document.getElementById('title').value = null;document.getElementById('des').value = null; return false;"></td>
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