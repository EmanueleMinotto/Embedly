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
 *
 * @method array extract(array $params)
 * @method array oembed(array $params)
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
     * @param string|null                      $apiKey     API key (optional).
     * @param \GuzzleHttp\ClientInterface|null $httpClient Alternative Guzzle HTTP client (optional).
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
        $request = $event->getRequest();

        if ('api.embed.ly' === $request->getHost()) {
            $requestQuery = $request->getQuery();

            $requestQuery->setEncodingType(false);
            $requestQuery->set('key', $this->apiKey);

            $request->setQuery($requestQuery);
        }
    }

    /**
     * The display based routes.
     *
     * @param string $method Display method, can be crop, resize, fill or empty.
     * @param array  $params Request options.
     *
     * @link http://embed.ly/docs/api/display/endpoints/1/display
     * @link http://embed.ly/docs/api/display/endpoints/1/display/crop
     * @link http://embed.ly/docs/api/display/endpoints/1/display/fill
     * @link http://embed.ly/docs/api/display/endpoints/1/display/resize
     *
     * @return string
     */
    public function display($method = null, array $params = array())
    {
        $httpClient = $this->getHttpClient();

        if (!is_null($method)) {
            $method = '/'.$method;
        }

        $params['key'] = $this->getApiKey();

        $response = $httpClient->get('http://i.embed.ly/1/display'.$method, [
            'query' => $params,
        ]);

        return (string) $response->getBody();
    }

    /**
     * Method used to encode URLs imploding them with a comma.
     *
     * @param array $values
     *
     * @return string
     */
    public static function encodeUrls(array $values)
    {
        return implode(',', array_map('urlencode', $values));
    }
}
