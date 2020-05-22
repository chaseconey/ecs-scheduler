@extends('layouts.app')

@section('content')

    <div class="row text-right">
        <div class="col">
            <form action="{{ route('services.store') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="cluster_id" value="{{ $cluster_id }}">
                <button class="btn btn-outline-secondary"><i class="fas fa-sync-alt"></i> Refresh</button>
            </form>
        </div>
    </div>

    {{ $services->links() }}
    <table class="table mt-4">
        <thead>
        <tr>
            <th>Name</th>
            <th>Cluster</th>
            <th>Scheduled</th>
        </tr>
        </thead>
        <tbody>
            @foreach($services as $service)
            <tr>
                <td><a href="{{ route('services.show', $service->id) }}">{{ $service->name }}</a></td>
                <td>{{ $service->cluster->name }}</td>
                <td>{!! $service->scheduled ? '<i class="fas text-success fa-check-circle"></i>' : '' !!}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $services->links() }}

@endsection
