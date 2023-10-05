@extends('layouts.app')

@section('content')

    <div class="container">

        <h1>Create Ads</h1>

        <form action="{{url('ads/' . $ad -> id)}}" method="POST" enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" value="{{$ad -> title}}">

                <span class="text-danger">
                    @error('title')
                        {{$message}}
                    @enderror
                </span>
            </div>

            <div class="mb-3">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control">{{$ad -> description}}</textarea>
                <span class="text-danger">
                    @error('description')
                    {{$message}}
                    @enderror
                </span>
            </div>

            <div class="mb-3">
                <label for="url">Url</label>
                <input type="url" name="url" id="url" class="form-control" value="{{$ad -> url}}">
                <span class="text-danger">
                    @error('url')
                    {{$message}}
                    @enderror
                </span>
            </div>

            <div class="mb-3">
                <label for="ad_owner">Ad Owner</label>
                <input type="text" name="ad_owner" id="ad_owner" class="form-control" value="{{$ad -> ad_owner}}">
                <span class="text-danger">
                    @error('ad_owner')
                    {{$message}}
                    @enderror
                </span>
            </div>

            <div class="mb-3">
                <label for="ad_owner_logo">Ad Owner Logo</label>
                <input type="file" name="ad_owner_logo" id="ad_owner_logo" class="form-control" value="{{old('ad_owner_logo')}}">

                <span class="text-danger">
                    @error('ad_owner_logo')
                    {{$message}}
                    @enderror
                </span>

                <img src="{{asset('storage/' . $ad->ad_owner_logo)}}" alt="logo" width="150">

            </div>



            <div class="mb-3">
                <label for="background_color">Background Color</label>
                <input type="color" name="background_color" id="background_color" class="form-control" value="{{$ad -> background_color}}">
                <span class="text-danger">
                    @error('background_color')
                    {{$message}}
                    @enderror
                </span>
            </div>

            <div class="mb-3">
                <label for="background_color">Background</label>
                <input type="file" name="background" id="background" class="form-control" value="{{old('background')}}">
                <span class="text-danger">
                    @error('background')
                    {{$message}}
                    @enderror
                </span>

                <img src="{{asset('storage/' . $ad->background)}}" alt="background" width="150">

            </div>

            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_published" role="switch" id="is_published" @checked($ad -> is_published) value="1">
                <label class="form-check-label" for="is_published">Published</label>
            </div>


            <button class="btn btn-primary my-3 w-100">Edit Ad</button>

        </form>

    </div>


@endsection
