@extends('layouts.app')

@section('content')

    <div class="container">

        <h1>Social Medias</h1>

        <a href="{{url('social-media/create')}}" class="btn btn-primary my-2">Create Social Media</a>

        @if(session()->has('success'))
        <div class="alert alert-success">
            {{session()->get('success')}}
        </div>

        @endif

        @if(count($socialMedias))

        <div class="table-responsive table-pr my-3 ">
            <table class="table table-striped table-dark align-middle">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Url</th>
                    <th>Logo</th>
                    <th>Actions</th>

                </tr>
                </thead>

                <tbody>

                @foreach($socialMedias as $socialMedia)

                    <tr>
                        <td>{{$socialMedia -> name}}</td>
                        <td>{{$socialMedia -> url}}</td>

                        <td>
                            <img src="{{asset('storage/' . $socialMedia -> logo)}}" alt="" width="100">
                        </td>



                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{url('social-media/' . $socialMedia -> id  . '/edit')}}">Edit</a></li>
                                    <li>
                                        <form action="{{url('social-media/' . $socialMedia -> id)}}" method="POST" onsubmit="return confirm('are you sure ?')">
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
