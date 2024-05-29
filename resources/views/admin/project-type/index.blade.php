@extends('admin.admin-layout')

@push('style')
    <style>
        .notification {
            padding: 10px;
            margin-top: 10px;
        }
    </style>
@endpush

@section('content')
    <div class="container mt-4">
        <h1>Project Types Index</h1>
        <a class="btn btn-primary mb-3" href="{{ route('admin.project-types.create') }}">Add New Project Type</a>
        
        @if (session('error'))
            <div class="alert alert-danger notification">
                {{ session('error') }}
            </div>
        @elseif (session('success'))
            <div class="alert alert-success notification">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table text-center">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Type</th>
                        <th scope="col">Description</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($project_types as $pt)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $pt->type }}</td>
                            <td>{{ $pt->description }}</td>
                            <td>
                                <a href="{{ route('admin.project-types.edit', $pt->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form method="POST" action="{{ route('admin.project-types.destroy', $pt->id) }}" style="display:inline-block;">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
