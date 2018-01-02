<?php

namespace App\ViewHelpers;

/**
 * Class AssetsHelper
 * @package App\ViewHelpers
 */
class AssetsHelper
{
    /**
     * Container
     *
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var string
     */
    protected $path;

    /**
     * AssetsHelper constructor.
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct($container, $path = '')
    {
        $this->container = $container;
        $this->path = trim($path, '/');
    }

    /**
     * @return string
     */
    public function render()
    {
        return $this->container->request->getUri()->getBasePath() . '/assets/' . $this->path;
    }
}