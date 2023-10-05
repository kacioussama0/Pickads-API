@extends('layouts.app')

@section('content')

    <div class="container">

        <h1>Edit Social Media</h1>

        <form action="{{url('social-media/' . $socialMedia -> id)}}" method="POST" enctype="multipart/form-data">

            @csrf
            @method('PUT')


            <div class="mb-3">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{$socialMedia->name}}">

                <span class="text-danger">
                    @error('name')
                    {{$message}}
                    @enderror
                </span>
            </div>


            <div class="mb-3">
                <label for="url">Url</label>
                <input type="url" name="url" id="url" class="form-control" value="{{$socialMedia->url}}">

                <span class="text-danger">
                    @error('url')
                    {{$message}}
                    @enderror
                </span>
            </div>


            <div class="mb-3">
                <label for="icon">Logo</label>
                <input type="file" name="logo" id="logo" class="form-control" value="{{old('logo')}}">
                <span class="text-danger">
                    @error('logo')
                    {{$message}}
                    @enderror
                </span>
            </div>

            <img src="{{asset('storage/' . $socialMedia->logo)}}" alt="{{$socialMedia->name}}" width="150" class="mt-3">


            <button class="btn btn-primary my-3 w-100">Update Social Media</button>

        </form>

    </div>

@endsection
