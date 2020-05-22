<?php

namespace App\Services;

use App\Cluster;
use App\Service;
use Aws\MultiRegionClient;

class EcsService
{
    private $client;

    public function __construct(MultiRegionClient $client)
    {
        $this->client = $client;
    }

    /**
     * Returns list of clusters by short name
     *
     * @return \Illuminate\Support\Collection
     */
    public function listClusters($region = 'us-west-2')
    {
        // TODO: hide production-like environments somehow
        $result = $this->client->listClusters(['@region' => $region]);

        \Log::info("Region is {$region}");
        \Log::info($result);

        return collect($result['clusterArns']);

    }

    public function listServices(Cluster $cluster)
    {
        // TODO: implement paginator (to get more than 100 items at a time)
        $result = $this->client->listServices(['cluster' => $cluster->name, 'maxResults' => 100, '@region' => $cluster->region]);

        return collect($result['serviceArns']);
    }

    public function describeService(Service $service)
    {
        $result = $this->client->describeServices([
            'cluster' => $service->cluster->name,
            '@region' => $service->cluster->region,
            'services' => [$service->arn],
        ]);

        return collect($result['services'][0]);
    }

    public function getServiceDesiredCount(Service $service)
    {
        $service = $this->describeService($service);

        return $service->get('desiredCount');
    }

    /**
     * https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ecs-2014-11-13.html#updateservice
     *
     * @param Service $service
     * @return \Illuminate\Support\Collection
     */
    public function shutdownService(Service $service)
    {
        // Set the desired count before we shut down for when we start again
        $oldDesiredCount = $this->getServiceDesiredCount($service);
        $service->desired_count = $oldDesiredCount;
        $service->save();

        $result = $this->client->updateService([
            'service' => $service->name,
            'desiredCount' => 0,
            'cluster' => $service->cluster->name
        ]);

        return collect($result['service']);
    }

    /**
     * https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ecs-2014-11-13.html#updateservice
     *
     * @param Service $service
     * @return \Illuminate\Support\Collection
     */
    public function startService(Service $service)
    {
        $result = $this->client->updateService([
            'service' => $service->name,
            'desiredCount' => $service->desired_count,
            'cluster' => $service->cluster->name
        ]);

        return collect($result['service']);
    }

}
