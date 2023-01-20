@extends('layouts.app')
@section('content')

<div class="container bg-info" style="width: 50%; margin:0 auto;padding:20px">
    <h1>Upload CSV FILE</h1>
    <form action="/uploadpost" method="post" enctype="multipart/form-data">
        @csrf
        <div class="bg-white" style="padding: 20px;">
            <input type="file" class="col-12 form-control my-3" name="file">
            @error('file')
                <div class="alert alert-danger" role="alert">{{ $message }}</div>
                @enderror
            @if(Session::has('messages'))
            @foreach(Session::get('messages') as $message)
            <div class="alert alert-danger" role="alert">
                {{$message->errors()[0]}} at line No-  {{$message->row()}}
            </div>
            @endforeach
            @endif
            <button class="btn btn-success my-5">Upload</button>
        </div>
    </form>
</div>

@endsection