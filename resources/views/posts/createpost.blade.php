@extends('layouts.app')

@section('content')
<form action="/confirm" method="post">
    @csrf
    <div class="container bg-info" style="width: 50%;padding:30px;border-radius:30px">
        <h1>Creat Post</h1>
        <div class="row my-3">
            <div class="col ">
                <p class="fw-bolder">Title :</p>
            </div>
            <div class="col">
                <input type="text" class="form-control" name="title" id="title"  value="{{old('title')}}">
                @error('title')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p class="fw-bolder">Description :</p>
            </div>
            <div class="col">
                <textarea class="form-control" name="des" id="des" cols="30" rows="10" value="{{old('des')}}"> {{old('des')}}</textarea>
                @error('des')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row my-3">
            <div class="col">

            </div>
            <div class="col">
                <div class="row">
                    <div class="col">
                        <input type="submit" class="form-control bg-success  fw-bolder text-light" value="Confirm">
                    </div>
                    <div class="col">
                        <input type="submit" class="form-control bg-light fw-bolder text-primary" value="Clear" onclick="document.getElementById('title').value = null;document.getElementById('des').value = null; return false;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection