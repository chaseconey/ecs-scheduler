@extends('layouts.app')

@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light">
            <li class="breadcrumb-item"><a href="{{ route('clusters.index') }}">Clusters</a></li>
            <li class="breadcrumb-item"><a href="{{ route('clusters.show', $service->cluster->id) }}">{{ $service->cluster->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $service->name }}</li>
        </ol>
    </nav>

    @if($details->isEmpty())
    <div class="alert alert-warning" role="alert">
        It looks like this service might not exist anymore. To keep things clean, hide unused services below.
    </div>
    @endif

    <div class="d-flex justify-content-between">
        <div>
            <h1>{{ $service->name }}</h1>
        </div>
        <div>
            <form action="{{ route('services.schedule', $service->id) }}" method="post" class="d-inline">
                {{ csrf_field() }}
                @if($service->scheduled)
                    <button class="btn btn-outline-primary"><i class="fas fa-calendar-times"></i> Unschedule</button>
                @else
                    <button class="btn btn-outline-primary"><i class="fas fa-calendar-plus"></i> Schedule</button>
                @endif
            </form>
            <form action="{{ route('services.power', $service->id) }}" method="post" class="d-inline">
                {{ csrf_field() }}
                @if($details->get('desiredCount') === 0)
                    <button class="btn btn-outline-success"><i class="fas fa-play-circle"></i> Start Service</button>
                @else
                    <button class="btn btn-outline-danger"><i class="fas fa-stop-circle"></i> Shutdown Service</button>
                @endif
            </form>
            <form action="{{ route('services.destroy', $service->id) }}" method="post" class="d-inline">
                {{ method_field('DELETE') }}

                {{ csrf_field() }}
                <button class="btn btn-outline-danger"><i class="fas fa-eye-slash"></i> Hide Service</button>
            </form>
        </div>
    </div>

    <hr>

    <div class="card">
        <div class="card-body">
            <table class="table table-sm">
                <tr>
                    <td>Service ARN</td>
                    <td>{{ $details->get('serviceArn') }}</td>
                </tr>
                <tr>
                    <td>Cluster ARN</td>
                    <td>{{ $details->get('clusterArn') }}</td>
                </tr>
                <tr>
                    <td>Desired Containers</td>
                    <td><span class="badge badge-light">{{ $details->get('desiredCount') }}</span></td>
                </tr>
                <tr>
                    <td>Running Containers</td>
                    <td>
                        <span class="badge badge-primary">{{ $details->get('runningCount') }}</span>
                    </td>
                </tr>
                <tr>
                    <td>Pending Containers</td>
                    <td>
                        <span class="badge badge-warning">{{ $details->get('pendingCount') }}</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>


    <div class="row mt-4">
        <div class="col">
            <h2>Events</h2>
            <div class="bg-dark shadow p-2" style="height: 375px; overflow: scroll">
                <code>
                    @foreach($details->get('events', []) as $event)
                        <b class="text-muted">[{{ Carbon\Carbon::parse($event['createdAt'])->diffForHumans() }}]</b>
                        <span class="text-white">{{ $event['message'] }}</span> <br>
                    @endforeach
                </code>
            </div>
        </div>
    </div>

@endsection
