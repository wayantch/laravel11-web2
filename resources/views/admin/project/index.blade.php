@extends('admin.admin-layout')


@section('content')

    <a class="btn btn-primary" href="{{route('admin.projects.create')}}">Add</a>
    @if (!empty(session('error')))
        <p class="text-danger">{{session('error')}}</p>
    @elseif(!empty(session('success')))
        <p class="text-success">{{session('success')}}</p>
    @endif

    <table class="table">
        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>Image</th>
                <th>Title</th>
                <th>Description</th>
                <th>Project Type</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($projects as $project)
                <tr class="text-center">
                    <td>{{ $loop->iteration  }}</td>
                    <td><img src="{{ asset(Storage::url($project->image)) }}" width="100"></td>
                    <td>{{ $project->title  }}</td>
                    <td>{{ $project->description}}</td>
                    <td>
                        <ul>
                            @foreach ($project->project_types as $pt)
                                <li>{{ $pt->type }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="d-flex justify-content-center align-items-center gap-2">
                        <a href="{{route('admin.projects.edit', $project->id) }}" class="btn btn-primary">Edit</a>
                        <form method="POST" action="{{ route('admin.projects.destroy', ['id' => $project->id]) }}">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection