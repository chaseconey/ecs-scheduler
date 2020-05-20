<?php

namespace App\Http\Controllers;

use App\Cluster;
use App\Service;
use App\Services\EcsService;
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
        $clusters = Cluster::all();

        return view('clusters.index', compact('clusters'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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


        return redirect()->to('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cluster = Cluster::with('services')->findOrFail($id);

        return view('clusters.show', compact('cluster'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
