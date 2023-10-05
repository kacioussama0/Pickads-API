@extends('layouts.app')

@section('content')

    <div class="container">

        <h1>Edit Category</h1>

        <form action="{{url('categories/' . $category->id)}}" method="POST" enctype="multipart/form-data">

            @csrf

            @method('PUT')

            <div class="mb-3">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{$category->name}}">

                <span class="text-danger">
                    @error('name')
                    {{$message}}
                    @enderror
                </span>
            </div>


            <div class="mb-3">
                <label for="slug">Slug</label>
                <input type="text" name="slug" id="slug" class="form-control" value="{{$category->slug}}">

                <span class="text-danger">
                    @error('slug')
                    {{$message}}
                    @enderror
                </span>
            </div>

            <div class="mb-3">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control">{{$category->description}}</textarea>
                <span class="text-danger">
                    @error('description')
                    {{$message}}
                    @enderror
                </span>
            </div>




            <div class="mb-3">
                <label for="icon">Icon</label>
                <input type="file" name="icon" id="icon" class="form-control" value="{{old('icon')}}">
                <span class="text-danger">
                    @error('icon')
                    {{$message}}
                    @enderror
                </span>
            </div>


            <img src="{{asset('storage/' . $category->icon)}}" alt="{{$category->name}}" width="150">


            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_published" role="switch" id="is_published" @checked($category->is_published) value="1">
                <label class="form-check-label" for="is_published">Published</label>
            </div>


            <button class="btn btn-primary my-3 w-100">Edit Category</button>

        </form>

    </div>


@endsection
