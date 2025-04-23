@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<h2>User Management</h2>
<a href="{{ route('users.create') }}" class="btn btn-success mb-3">Create User</a>
<a href="{{ route('roles.index') }}" class="btn btn-primary mb-3">Roles</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Roles</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ implode(', ', $user->roles->pluck('name')->toArray()) }}</td>
            <td>{{ $user->active ? 'Active' : 'Deactivated' }}</td>
            <td>
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
