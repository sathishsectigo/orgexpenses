@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Project</h2>
    
    <form action="{{ route('projects.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Project Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
            <label for="reporting_manager_id" class="form-label">Reporting In-Charge</label>
            <select class="form-control" id="reporting_manager_id" name="reporting_manager_id" required>
                <option value="">Select Reporting In-Charge</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Create</button>
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
