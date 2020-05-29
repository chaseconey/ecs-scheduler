<?php

namespace App\Http\Controllers;

use App\Cluster;
use App\Service;
use App\Services\EcsService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ClusterController extends Controller
{

    /**
     * @var EcsService
     */
    private $service;

    public function __construct(EcsService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $clusters = Cluster::withCount([
            'services',
            'services as deleted_services_count' => function (Builder $query) {
                $query->onlyTrashed();
            }])->get();

        return view('clusters.index', compact('clusters'));
    }

    /**
     * Upsert all clusters for all configured regions
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        foreach (config('app.configured_regions') as $region) {
            $clusters = $this->service->listClusters($region);

            foreach ($clusters as $cluster) {
                Cluster::firstOrCreate([
                    'arn' => $cluster,
                    'region' => $region
                ]);
            }
        }

        laraflash('Clusters have been successfully imported.')->success();

        return redirect()->to('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show($id)
    {
        $services = Service::where('cluster_id', $id)
            ->with('cluster')
            ->withTrashed()
            ->paginate(10);

        $cluster = Cluster::findOrFail($id);

        return view('clusters.show', [
            'cluster' => $cluster,
            'services' => $services
        ]);
    }

}
