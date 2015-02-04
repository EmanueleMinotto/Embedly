<?php

namespace EmanueleMinotto\Embedly\Twig;

use EmanueleMinotto\Embedly\Client;
use Twig_Extension;
use Twig_SimpleFunction;

/**
 * Twig extension for embed.ly APIs.
 *
 * @author Emanuele Minotto <minottoemanuele@gmail.com>
 */
class EmbedlyExtension extends Twig_Extension
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Class constructor with optional client.
     *
     * @param Client|null $client
     */
    public function __construct(Client $client = null)
    {
        $this->client = $client ?: new Client();
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('embedly_display', array($this->client, 'display')),
            new Twig_SimpleFunction('embedly_extract', array($this->client, 'extract')),
            new Twig_SimpleFunction('embedly_oembed', array($this->client, 'oembed')),
        ];
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'emanueleminotto_embedly_twigextension';
    }
}
