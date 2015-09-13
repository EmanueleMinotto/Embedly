<?php

namespace EmanueleMinotto\Embedly\Tests\Twig;

use EmanueleMinotto\Embedly\Twig\EmbedlyExtension;
use Twig_Test_IntegrationTestCase;

/**
 * @author Emanuele Minotto <minottoemanuele@gmail.com>
 *
 * @coversDefaultClass \EmanueleMinotto\Embedly\Twig\EmbedlyExtension
 */
class EmbedlyExtensionTest extends Twig_Test_IntegrationTestCase
{
    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
        return [
            new EmbedlyExtension(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFixturesDir()
    {
        return dirname(__FILE__).'/Fixtures/';
    }
}
