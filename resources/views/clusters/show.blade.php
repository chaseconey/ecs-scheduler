@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light">
                <li class="breadcrumb-item"><a href="{{ route('clusters.index') }}">Clusters</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $cluster->name }}</li>
            </ol>
        </nav>
        <div>
            <form action="{{ route('services.store') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="cluster_id" value="{{ $cluster->id }}">
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
                <td>
                    @if($service->trashed())
                        <span class="text-muted">{{ $service->name }} (hidden)</span>
                    @else
                        <a href="{{ route('services.show', $service->id) }}">{{ $service->name }}</a>
                    @endif
                </td>
                <td>{{ $service->cluster->name }}</td>
                <td>{!! $service->scheduled ? '<i class="fas text-success fa-check-circle"></i>' : '' !!}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $services->links() }}

@endsection
