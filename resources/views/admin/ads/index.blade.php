@extends('layouts.app')

@section('content')

    <div class="container">

        <h1>Ads</h1>

        <a href="{{url('ads/create')}}" class="btn btn-primary my-2">Create Ad</a>

        @if(session()->has('success'))
        <div class="alert alert-success">
            {{session()->get('success')}}
        </div>

        @endif

        @if(count($ads))

        <div class="table-responsive table-pr my-3 ">
            <table class="table table-striped table-dark align-middle">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Url</th>
                    <th>Ad Owner</th>
                    <th>Ad Owner Logo</th>
                    <th>Background</th>
                    <th>Background Color</th>
                    <th>Is Published</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>

                @foreach($ads as $ad)

                    <tr>
                        <td>{{$ad -> title}}</td>
                        <td>{{$ad -> description}}</td>
                        <td>{{$ad -> url}}</td>
                        <td>{{$ad -> ad_owner}}</td>
                        <td>
                            <img src="{{asset('storage/' . $ad -> ad_owner_logo)}}" alt="" width="100">
                        </td>
                        <td>
                            <img src="{{asset('storage/' . $ad -> background)}}" alt="" width="100">
                        </td>

                        <td>
                            <div style="width: 30px;height: 30px ; background-color: {{$ad -> background_color}}" class="rounded-circle"></div>
                        </td>

                        <td>
                            {{$ad -> is_published ? 'Yes' : 'No'}}
                        </td>

                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{url('ads/' . $ad -> id  . '/edit')}}">Edit</a></li>
                                    <li>
                                        <form action="{{url('ads/' . $ad -> id)}}" method="POST" onsubmit="return confirm('are you sure ?')">
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
