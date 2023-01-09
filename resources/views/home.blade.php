@extends('layouts.app')

@section('content')
<div class="container">
    @csrf
    <div class="row justify-content-center">
        <div class="row justify-content-center my-5">
            <form action="/search" method="post" class="col-6 row">
                @csrf
                <div class="col-8 text-center"><input type="text" class="form-control" name="searchitem" id="searchitem"></div>
                <button class="btn btn-info col-4">Search</button>
            </form>
            <div class="col-2 text-center"><a href="/createpost" style="text-decoration: none;color:white;padding:15px;background-color:blue;border-radius:15px">Upload</a></div>
            <div class="col-2 text-center"><a href="/createpost" style="text-decoration: none;color:white;padding:15px;background-color:blue;border-radius:15px">Download</a></div>
            <div class="col-2 text-center"> <a href="/createpost" style="text-decoration: none;color:white;padding:15px;background-color:blue;border-radius:15px">Add</a></div>
        </div>
        <table class="table bg-white" style="width: 60%;">
            <thead>
                <tr>
                    <th scope="col">Post Title</th>
                    <th scope="col">Post Description</th>
                    <th scope="col">Posted User</th>
                    <th scope="col">Posted Date</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @if(count($posts)===0)
                <tr>
                    <td colspan="6" class="text-center">There is no posts</td>
                </tr>
                @else
                @foreach($posts as $post)
                <tr>
                    <th scope="row"><a href="">
                            {{$post->title}}</a>
                    </th>
                    <td>{{$post->description}}</td>
                    <td>{{$post->pname}}</td>
                    <td>{{$post->created_at}}</td>
                    <td><a href="updatepost/{{$post->id}}">Edit</a></td>
                    <td><a href="">
                            <form id="delete-form" method="POST" action="delete/{{$post->id}}">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}

                                <div class="form-group">
                                    <button type="submit" onclick="return confirm('Are You Sure Want To Delete')" class="delete-user">delete</button>
                                </div>
                            </form>
                        </a></td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection