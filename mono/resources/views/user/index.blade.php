@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Users</h1>
        <a href="{{ route('user.create') }}" class="btn btn-success mb-3">Create New User</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->nama }}</td>
                        <td>{{ $user->status }}</td>
                        <td>
                            <a href="{{ route('user.show', $user->id) }}" class="btn btn-info">View</a>
                            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection