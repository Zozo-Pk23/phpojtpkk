@extends('layouts.app')
@section('content')

<div class="container bg-info" style="width: 50%; margin:0 auto;padding:20px">
    <h1>Upload CSV FILE</h1>
    <form action="/uploadpost" method="post" enctype="multipart/form-data">
        @csrf
        <div class="bg-white" style="padding: 20px;">
            <input type="file" class="col-12 form-control" name="file">
            @if(count($errors->getMessages()) > 0)
            <div class="alert alert-danger alert-dismissible" role="alert">
                <strong>Validation Errors:</strong>
                <ul>
                    @foreach($errors->getMessages() as $errorMessages)
                    @foreach($errorMessages as $errorMessage)
                    <li>
                        {{ $errorMessage }}
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    </li>
                    @endforeach
                    @endforeach
                </ul>
            </div>@endif
            <button class="btn btn-success my-5">Upload</button>
        </div>
    </form>
</div>

@endsection