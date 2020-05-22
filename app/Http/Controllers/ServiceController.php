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
     * @param \Illuminate\Http\Request $request
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

        laraflash("Services have been imported for {$cluster->name}.")->success();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
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

        laraflash("{$service->name}'s schedule toggled.")->success();

        return redirect()->back();
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function hide($id)
    {
        $service = Service::findOrFail($id);

        $service->hidden = true;
        $service->save();

        laraflash("{$service->name}'s now hidden.")->success();

        return redirect()->route('clusters.show', [$service->cluster->id]);
    }

    public function power($id)
    {
        $service = Service::findOrFail($id);

        $desiredCount = $this->service->getServiceDesiredCount($service);

        if ($desiredCount === 0) {
            $this->service->startService($service);
            laraflash("{$service->name} has been started - it may take a minute to complete.")->success();
        } else {
            $this->service->shutdownService($service);
            laraflash("{$service->name} has been stopped - it may take a minute to complete.")->success();
        }

        return redirect()->back();
    }

}
