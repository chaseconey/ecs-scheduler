<?php

namespace App\Console\Commands;

use App\Service;
use App\Services\EcsService;
use Illuminate\Console\Command;

class StartScheduledServices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ecs:start-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start all services that have been scheduled';
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
        $services = Service::where('scheduled', 1)->get();

        $this->info(count($services) . ' services scheduled to start');

        foreach ($services as $service) {
            $this->info("Starting up {$service->name}");
            $this->service->startService($service);
        }
    }
}
