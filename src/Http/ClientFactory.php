<?php

declare(strict_types=1);

namespace Coresender\Http;

use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\Psr18ClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;

class ClientFactory
{
    public static function createPublicClient()
    {

    }

    public static function createOAuthClient(string $accessToken, string $endpoint): PluginClient
    {
        $urlFactory = Psr17FactoryDiscovery::findUrlFactory();

        $plugins = [
            new Plugin\AddHostPlugin($urlFactory->createUri($endpoint)),
            new Plugin\HeaderDefaultsPlugin([
                'Authorization' => 'Bearer '.$accessToken,
            ]),
        ];

        $client = Psr18ClientDiscovery::find();

        return new PluginClient($client, $plugins);
    }

    public static function createBasicAuthClient(string $accountId, string $apiKey, string $endpoint): PluginClient
    {
        $urlFactory = Psr17FactoryDiscovery::findUrlFactory();

        $plugins = [
            new Plugin\AddHostPlugin($urlFactory->createUri($endpoint)),
            new Plugin\HeaderDefaultsPlugin([
                'Authorization' => 'Basic '.base64_encode(sprintf('%s:%s', $accountId, $apiKey)),
            ]),
        ];

        $client = Psr18ClientDiscovery::find();

        return new PluginClient($client, $plugins);
    }
}
