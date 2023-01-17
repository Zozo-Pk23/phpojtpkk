@extends('layouts.app')

@section('content')
<div class="container">
    @csrf
    <div class="row justify-content-center">
        <div class="row justify-content-center my-5">
            <form action="/search" method="post" class="col-6 row">
                @csrf
                <div class="col-8 text-center"><input type="text" placeholder="Search title or description" class="form-control" name="searchitem" id="searchitem"></div>
                <button class="btn btn-info col-4">Search</button>
            </form>
            <div class="col-2 text-center"><a href="/upload" class="btn btn-info form-control">Upload</a></div>
            <form action="/download" method="post" class="col-2 text-center">
                @csrf
                <button class="btn btn-info form-control">Download</button>
            </form>
            <div class="col-2 text-center"> <a href="/createpost" class="btn btn-info form-control">Add</a></div>
        </div>
        <table class="table bg-white" id="mytable" style="width: 60%;">
            <thead>
                <tr>
                    <th scope="col">Post Title</th>
                    <th scope="col">Post Description</th>
                    <th scope="col">Status</th>
                    @if($type==0)
                    <th scope="col">Posted User</th>
                    <th scope="col">Updated User</th>
                    @endif
                    <th scope="col">Posted Date</th>
                    <th scope="col">Updated Date</th>
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
                    <th scope="row">
                        <a data-bs-target="#exampleModal{{$post->id}}" data-bs-toggle="modal" class="MainNavText" id="MainNavHelp" href="#myModal">{{$post->title}}</a>
                    </th>
                    <td>{{$post->description}}</td>

                    @if($post->status==1)
                    <td>
                        Active
                    </td>
                    @else
                    <td>
                        Not Active
                    </td>
                    @endif
                    @if($type==0)
                    <td>{{$post->pname}}</td>
                    <td>{{$post->uname}}</td>
                    @endif
                    <td>
                        {{date('d-m-Y', strtotime($post->created_at))}}
                    </td>
                    <td>{{date('d-m-Y', strtotime($post->updated_at))}}</td>
                    <td><a href="updatepost/{{$post->id}}">Edit</a></td>
                    <td>
                        <!-- <a href="">
                            <form id="delete-form" method="POST" action="delete/{{$post->id}}">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}

                                <div class="form-group">
                                    <button type="submit" onclick="return confirm('Are You Sure Want To Delete')" class="delete-user">delete</button>
                                </div>
                            </form>
                        </a> -->
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#Delete{{$post->id}}">Delete</button>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {!! $posts->links() !!}
        </div>
        @foreach($posts as $post)
        <div class="modal" tabindex="-1" id="Delete{{$post->id}}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure that you would like to Delete?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <form id="delete-form" method="POST" action="delete/{{$post->id}}">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}

                            <div class="form-group">
                                <button class="btn-danger btn">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @foreach($posts as $post)
        <div class="modal fade" id="exampleModal{{$post->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <form action="">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <table class="table">
                                <tr>
                                    <td>Title</td>
                                    <td>
                                        {{$post->title}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Description</td>
                                    <td>
                                        {{$post->description}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>
                                        {{$post->status}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Crated At</td>
                                    <td>
                                        {{$post->created_at}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Update At</td>
                                    <td>
                                        {{$post->updated_at}}
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @endforeach
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#myTable').dataTable();
    });
</script>
@endsection