<?php

namespace Maxbond\Mueble\Factories;

use Maxbond\Mueble\Interfaces\ViewInterface;

/**
 * Dynamic create view engine class
 * @package Maxbond\Mueble\Factories
 */
class ViewEngineFactory
{
    const SUFFIX = 'View';

    const NAMESPACE = 'Maxbond\Mueble\View\\';

    /**
     * @param $config
     * @return ViewInterface
     */
    public static function get($config) : ViewInterface
    {
        $class = ucfirst($config['view.engine']) . static::SUFFIX;
        $class = static::NAMESPACE . $class;

        return new $class(
            $config['view.templates'], $config['view.cache'] ?? false
        );
    }
}