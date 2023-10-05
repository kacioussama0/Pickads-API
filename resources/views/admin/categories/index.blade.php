@extends('layouts.app')

@section('content')

    <div class="container">

        <h1>Categories</h1>

        <a href="{{url('categories/create')}}" class="btn btn-primary my-2">Create Category</a>

        @if(session()->has('success'))
        <div class="alert alert-success">
            {{session()->get('success')}}
        </div>

        @endif

        @if(count($categories))

        <div class="table-responsive table-pr my-3 ">
            <table class="table table-striped table-dark align-middle">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Description</th>
                    <th>Icon</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>

                @foreach($categories as $category)

                    <tr>
                        <td>{{$category -> name}}</td>
                        <td>{{$category -> slug}}</td>
                        <td>{{$category -> description}}</td>

                        <td>
                            <img src="{{asset('storage/' . $category -> icon)}}" alt="" width="100">
                        </td>

                        <td>
                            {{$category -> is_published ? 'Yes' : 'No'}}
                        </td>

                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{url('categories/' . $category -> id  . '/edit')}}">Edit</a></li>
                                    <li>
                                        <form action="{{url('categories/' . $category -> id)}}" method="POST" onsubmit="return confirm('are you sure ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="dropdown-item" href="#">Delete</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>

                @endforeach

                </tbody>
            </table>
        </div>

        @else
            <div class="alert alert-danger my-3">
               <h3 class="display-3">No Ads</h3>
            </div>

        @endif
    </div>


@endsection
