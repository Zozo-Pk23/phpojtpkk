@extends('layouts.app')
@section('content')

<div class="container bg-info" style="width: 50%; margin:0 auto;padding:20px">
    <h1>Upload CSV FILE</h1>
    <form action="/uploadpost" method="post" enctype="multipart/form-data">
        @csrf
        <div class="bg-white" style="padding: 20px;">
            <input type="file" class="col-12 form-control" name="file">
            @error('file')
            <div class="text-danger">{{ $message }}</div>
            @enderror
            @if($errors->any())
            <div class="text-danger">There must be some error in your excel file</div>
            <ol>
                @foreach($errors->all() as $error)
                <li>{{$error}}</li>
                @endforeach
            </ol>
            @endif
            <button class="btn btn-success my-5">Upload</button>
        </div>
    </form>
</div>

@endsection