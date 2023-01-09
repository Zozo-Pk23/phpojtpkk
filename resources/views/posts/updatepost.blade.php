@extends('layouts.app')

@section('content')
<form action="/updateblade/{{$post->id}}" method="post">
    @csrf

    <input type="hidden" name="id" value="{{$post->id}}">
    <input type="hidden" name="status" value="{{$post->status}}">
    <input type="hidden" name="description" value="{{$post->description}}">
    <div class="container bg-info" style="width: 50%;padding:30px;border-radius:30px">
        <h1>Edit Post</h1>
        <div class="row my-3">
            <div class="col ">
                <p class="fw-bolder">Title :</p>
            </div>
            <div class="col">
                <input type="text" class="form-control" name="title" id="title" value="{{$post->title}}">
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
                <textarea class="form-control" name="des" id="des" cols="30" rows="10">{{$post->description}}</textarea>
                @error('des')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p class="fw-bolder">Status :</p>
            </div>
            <div class="col">
                This is checkbox
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