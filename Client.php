<?php

namespace EmanueleMinotto\Embedly;

use GuzzleHttp\Client as GuzzleHttp_Client;
use GuzzleHttp\ClientInterface as GuzzleHttp_ClientInterface;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use GuzzleHttp\Event\BeforeEvent;

/**
 * Embed.ly API client.
 *
 * @author Emanuele Minotto <minottoemanuele@gmail.com>
 */
class Client extends GuzzleClient
{
    /**
     * Embed.ly API version.
     */
    const API_VERSION = 1;

    /**
     * API key.
     *
     * @var string|null
     */
    private $apiKey = null;

    /**
     * Class constructor.
     *
     * @param string|null                      $apiKey API key (optional).
     * @param \GuzzleHttp\ClientInterface|null $client Alternative Guzzle HTTP client (optional).
     */
    public function __construct($apiKey = null, GuzzleHttp_ClientInterface $httpClient = null)
    {
        // Embed.ly API description
        $description = json_decode(file_get_contents(__DIR__.'/description.json'), true);

        parent::__construct(
            $httpClient ?: new GuzzleHttp_Client(),
            new Description($description)
        );

        $this->setApiKey($apiKey);

        $this->getHttpClient()->getEmitter()->once(
            'before',
            function (BeforeEvent $event) {
                $this->getBeforeEvent($event);
            }
        );
    }

    /**
     * API key setter.
     *
     * @param string|null $apiKey
     */
    public function setApiKey($apiKey = null)
    {
        $this->apiKey = (string) $apiKey;

        return $this;
    }

    /**
     * API key getter.
     *
     * @return string|null
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Guzzle event used to set the key parameter.
     *
     * @param BeforeEvent $event
     */
    private function getBeforeEvent(BeforeEvent $event)
    {
        if ('api.embed.ly' === $event->getRequest()->getHost()) {
            $event->getRequest()->getQuery()->set('key', $this->apiKey);
        }

        $path = $event->getRequest()->getPath();
        if ('/1/display' === substr($path, 0, 10)) {
            $event->getRequest()->setHost('i.embed.ly');
        }
    }
}
