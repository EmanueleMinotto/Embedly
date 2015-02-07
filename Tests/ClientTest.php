<?php

namespace EmanueleMinotto\Embedly\Tests;

use EmanueleMinotto\Embedly\Client;
use PHPUnit_Framework_TestCase;

/**
 * @author Emanuele Minotto <minottoemanuele@gmail.com>
 *
 * @coversDefaultClass \EmanueleMinotto\Embedly\Client
 */
class ClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     */
    public function testApiKeyContruct()
    {
        $key = 'test';

        $client = new Client($key);

        $this->assertSame($key, $client->getApiKey());
    }

    /**
     * @covers ::setApiKey
     */
    public function testApiKeySet()
    {
        $key = 'test';

        $client = new Client();
        $client->setApiKey($key);

        $this->assertSame($key, $client->getApiKey());
    }

    public function testOembed()
    {
        $client = new Client($_ENV['api_key']);
        $embed = $client->oembed([
            'url' => $_ENV['url'],
        ]);

        $this->assertInternalType('array', $embed);

        $this->assertArrayHasKey('type', $embed);
        $this->assertContains($embed['type'], ['photo', 'video', 'rich', 'link', 'error']);

        switch ($embed['type']) {
            case 'error':
                $this->assertArrayHasKey('error_code', $embed);
                $this->assertArrayHasKey('error_message', $embed);
                $this->assertArrayHasKey('url', $embed);

                break;
            case 'photo':
                $this->assertArrayHasKey('height', $embed);
                $this->assertArrayHasKey('url', $embed);
                $this->assertArrayHasKey('width', $embed);

                break;
            case 'rich':
            case 'video':
                $this->assertArrayHasKey('html', $embed);

                break;
        }
    }

    public function testOembedMultiple()
    {
        $client = new Client($_ENV['api_key']);
        $embeds = $client->oembed([
            'urls' => [
                $_ENV['url'],
                'http://www.example.com/',
            ],
        ]);

        $this->assertInternalType('array', $embeds);
        $this->assertCount(2, $embeds);

        foreach ($embeds as $embed) {
            $this->assertInternalType('array', $embed);

            $this->assertArrayHasKey('type', $embed);
            $this->assertContains($embed['type'], ['photo', 'video', 'rich', 'link', 'error']);
        }
    }

    public function testExtract()
    {
        if (!$_ENV['api_key']) {
            $this->markTestSkipped('API key is required.');
        }

        $client = new Client($_ENV['api_key']);
        $extracted = $client->extract([
            'url' => $_ENV['url'],
        ]);

        $this->assertInternalType('array', $extracted);
        $this->assertContains(
            $extracted['type'],
            ['html', 'text', 'image', 'video', 'audio', 'rss', 'xml', 'atom', 'json', 'ppt', 'link', 'error']
        );

        $keys = [
            'authors',
            'cache_age',
            'content',
            'description',
            'entities',
            'favicon_colors',
            'favicon_url',
            'images',
            'keywords',
            'lead',
            'media',
            'offset',
            'original_url',
            'provider_display',
            'provider_name',
            'provider_url',
            'published',
            'related',
            'safe',
            'title',
            'type',
            'url',
        ];

        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $extracted);
        }

        switch ($extracted['type']) {
            case 'photo':
                $this->assertArrayHasKey('height', $extracted);
                $this->assertArrayHasKey('url', $extracted);
                $this->assertArrayHasKey('width', $extracted);

                break;
            case 'rich':
            case 'video':
                $this->assertArrayHasKey('height', $extracted);
                $this->assertArrayHasKey('html', $extracted);
                $this->assertArrayHasKey('width', $extracted);

                break;
        }
    }

    /**
     * @covers ::display
     * @dataProvider displayDataProvider
     */
    public function testDisplay($method, array $options = array())
    {
        if (!$_ENV['api_key']) {
            $this->markTestSkipped('API key is required.');
        }

        $client = new Client($_ENV['api_key']);
        $options['url'] = $_ENV['picture_url'];

        $displayed = $client->display($method, $options);

        $this->assertInternalType('string', $displayed);
    }

    public function displayDataProvider()
    {
        return [
            [null, []],
            ['crop', [
                'height' => 50,
                'width' => 50,
            ]],
            ['resize', []],
            ['fill', [
                'color' => '000',
                'height' => 50,
                'width' => 50,
            ]],
        ];
    }
}
