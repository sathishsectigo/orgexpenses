@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Projects</h2>
    <a href="{{ route('projects.create') }}" class="btn btn-primary mb-3">Create New Project</a>
    
    <table class="table">
        <tr>
            <th>Name</th>
            <th>Reporting In-Charge</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        @foreach ($projects as $project)
        <tr>
            <td>{{ $project->name }}</td>
            <td>{{ $project->reportingInCharge->name ?? 'N/A' }}</td>
            <td>{{ $project->active ? "Active" : "In-active" }}</td>
            <td>
                <a href="{{ route('projects.show', $project->id) }}" class="btn btn-info btn-sm">View</a>
                <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
