@extends('layouts.app')

@section('content')
    <h1>Users</h1>
    <a href="{{ route('user.create') }}">Create New User</a>
    <ul>
        @foreach ($users as $user)
            <li>
                {{ $user->nama }} - {{ $user->status }}
                <a href="{{ route('user.show', $user->id) }}">View</a>
                <a href="{{ route('user.edit', $user->id) }}">Edit</a>
                <form action="{{ route('user.destroy', $user->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection