<?php

namespace App\Http\Controllers;

use App\Cluster;
use App\Service;
use App\Services\EcsService;
use Illuminate\Http\Request;

class ServiceController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, EcsService $service)
    {
        $cluster = Cluster::findOrFail($request->cluster_id);
        $services = $service->listServices($cluster);

        foreach ($services as $service) {
            Service::firstOrCreate([
                'arn' => $service,
                'cluster_id' => $cluster->id
            ]);
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $service = Service::findOrFail($id);
        $details = $this->service->describeService($service);

        return view('services.show', compact('details', 'service'));
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function schedule($id)
    {
        $service = Service::findOrFail($id);

        $service->scheduled = !$service->scheduled;
        $service->save();

        return redirect()->back();

    }

    public function power($id)
    {
        $service = Service::findOrFail($id);

        $desiredCount = $this->service->getServiceDesiredCount($service);

        if ($desiredCount === 0) {
            $this->service->startService($service);
        } else {
            $this->service->shutdownService($service);
        }

        return redirect()->back();
    }

}
