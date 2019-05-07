<?php

namespace Maxbond\Mueble\Factories;

use Maxbond\Mueble\Database\DBDriverInterface;

/**
 * Dynamic create repository class
 * @package Maxbond\Mueble\Factories
 */
class RepositoryFactory
{
    const SUFFIX = 'Repository';

    const NAMESPACE = 'App\Repositories\\';

    /**
     * @var array
     */
    protected $config;

    /**
     * @var DBDriverInterface
     */
    protected $dbDriver;

    /**
     * RepositoryFactory constructor.
     * @param array $config
     * @param DBDriverInterface $dbDriver
     */
    public function __construct(array $config, DBDriverInterface $dbDriver)
    {
        $this->config = $config;
        $this->dbDriver = $dbDriver;
    }

    /**
     * @param $repository
     * @return mixed
     */
    public function get($repository)
    {
        $class = ucfirst($repository) . static::SUFFIX;
        $class = static::NAMESPACE . ucfirst($this->config['driver']) . '\\' . $class;

        return new $class($this->dbDriver);
    }
}