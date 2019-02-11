@extends('layouts.app')
@section('content')
<div class="container mt-3 pt-3">
    <table class="table table-bordered">
        <thead>
        <th>Name</th>
        <th>Count members in file</th>
        <th>Count members ready</th>
        <th>Time ready</th>
        <th>State</th>
        <th>Error message</th>
        <th>Time upload</th>
        </thead>
        @if($files)
        <tbody>
            @foreach($files as $file)
        <tr>
            <td>{{$file->name}}</td>
            <td>{{$file->all_members}}</td>
            <td>{{$file->ready_members}}</td>
            <td>{{$file->read_at}}</td>
            <td>{{$file->state}}</td>
            <td>{{$file->message}}</td>
            <td>{{$file->created_at}}</td>
        </tr>
            @endforeach
        </tbody>
            @else
            <h3>No contents</h3>
        @endif
    </table>
</div>
@endsection