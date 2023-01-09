@extends('layouts.app')

@section('content')
<form action="/create" method="post">
    @csrf
    <div class="container bg-info" style="width: 50%;padding:30px;border-radius:30px">
        <h1>Confirm Post</h1>
        <input type="hidden" name="title" value="{{$post->title}}">
        <input type="hidden" name="des" value="{{$post->des}}">
        <div class="row my-3">
            <div class="col ">
                <p class="fw-bolder">Title :</p>
            </div>
            <div class="col">
                <p>{{$post->title}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p class="fw-bolder">Description :</p>
            </div>
            <div class="col">
                <p>{{$post->des}}</p>
            </div>
        </div>
        <div class="row my-3">
            <div class="col">

            </div>
            <div class="col">
                <div class="row">
                    <div class="col">
                        <a href="{{ route('home') }}"><button class="btn btn-default bg-white">Cancel</button></a>
                    </div>
                    <div class="col">
                        <input type="submit" class="form-control bg-success  fw-bolder text-light" value="Confirm">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection