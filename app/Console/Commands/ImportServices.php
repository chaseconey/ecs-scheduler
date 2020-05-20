<?php

namespace App\Console\Commands;

use App\Cluster;
use App\Service;
use App\Services\EcsService;
use Illuminate\Console\Command;

class ImportServices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ecs:import-services';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all services for given cluster';
    /**
     * @var EcsService
     */
    private $service;

    /**
     * Create a new command instance.
     *
     * @param EcsService $service
     */
    public function __construct(EcsService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $clusters = Cluster::all();

        foreach ($clusters as $cluster) {
            $services = $this->service->listServices($cluster);

            foreach ($services as $service) {
                Service::firstOrCreate([
                    'arn' => $service,
                    'cluster_id' => $cluster->id
                ]);
            }
        }

    }
}
