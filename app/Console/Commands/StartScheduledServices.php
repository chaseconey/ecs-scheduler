<?php

namespace App\Console\Commands;

use App\Service;
use App\Services\EcsService;
use Illuminate\Console\Command;
use Aws\Ecs\Exception\EcsException;
use App\Notifications\ServicesStarted;
use Illuminate\Support\Facades\Notification;

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
    private $client;

    /**
     * Create a new command instance.
     *
     * @param EcsService $client
     */
    public function __construct(EcsService $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $services = Service::where('scheduled', 1)->get();

        foreach ($services as $service) {
            try {
                $this->client->startService($service);
            } catch (EcsException $e) {
                $this->info("Exception when starting service: " . json_encode($e->getCommand()->toArray()));
            }
        }

        if ($services->count() > 0) {
            Notification::route('slack', config('services.slack.webhook_url'))
                ->notify(new ServicesStarted($services));
        }
    }
}
