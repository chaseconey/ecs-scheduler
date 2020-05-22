<?php

namespace App\Console\Commands;

use App\Service;
use App\Services\EcsService;
use Illuminate\Console\Command;

class ShutdownScheduledServices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ecs:shutdown-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shutdown all services that have been scheduled to shutdown';
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

        \Log::info(count($services) . ' services scheduled to shutdown');

        foreach ($services as $service) {
            \Log::info("Shutting down {$service->name}");
            $this->service->shutdownService($service);
        }

    }
}
