@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Influencers</h1>

    <div class="table-responsive table-pr my-3 ">

        <table class="table table-striped table-dark align-middle">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Category</th>
                    <th>Banned</th>
                    <th>Gender</th>
                </tr>
            </thead>

            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{$user->first_name . ' ' . $user-> last_name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->category->name}}</td>
                        <td>{{$user->is_banned ? 'Yes' : 'No'}}</td>
                        <td>{{$user->gender}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</div>
@endsection
