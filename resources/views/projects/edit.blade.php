@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Project</h2>

    <form action="{{ route('projects.update', $project->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Project Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $project->name }}" required>
        </div>

        <div class="mb-3">
            <label for="reporting_in_charge" class="form-label">Reporting In-Charge</label>
            <select class="form-control" id="reporting_in_charge" name="reporting_in_charge" required>
                <option value="">Select Reporting In-Charge</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $project->reporting_in_charge == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
