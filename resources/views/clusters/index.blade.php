@extends('layouts.app')

@section('content')

    <div class="row text-right">
        <div class="col">
            <form action="{{ route('clusters.store') }}" method="post">
                {{ csrf_field() }}
                <button class="btn btn-outline-secondary"><i class="fas fa-sync-alt"></i> Refresh</button>
            </form>
        </div>
    </div>

    <table class="table mt-4">
        <thead>
        <tr>
            <th>Name</th>
            <th>Region</th>
            <th># Services</th>
        </tr>
        </thead>
        <tbody>
        @foreach($clusters as $cluster)
            <tr>
                <td><a href="{{ route('clusters.show', $cluster->id) }}">{{ $cluster->name }}</a></td>
                <td>{{ $cluster->region }}</td>
                <td>{{ $cluster->visible_services_count }} <span class="text-muted">({{ $cluster->hidden_services_count }} hidden)</span></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
