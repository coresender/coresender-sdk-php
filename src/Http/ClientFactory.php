<?php

declare(strict_types=1);

namespace Coresender\Http;

use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\Psr18ClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;

class ClientFactory
{
    public static function createClient(string $endpoint)
    {
        $urlFactory = Psr17FactoryDiscovery::findUrlFactory();

        $plugins = [
            new Plugin\AddHostPlugin($urlFactory->createUri($endpoint)),
        ];

        $client = Psr18ClientDiscovery::find();

        return new PluginClient($client, $plugins);
    }
}
