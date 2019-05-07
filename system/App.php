<?php

namespace Maxbond\Mueble;

use Maxbond\Mueble\Database\DBDriverInterface;
use Maxbond\Mueble\Exceptions\DBException;
use Maxbond\Mueble\Exceptions\UsageException;
use Maxbond\Mueble\Factories\RepositoryFactory;
use Maxbond\Mueble\Factories\ViewEngineFactory;
use Maxbond\Mueble\Interfaces\ViewInterface;

/**
 * Application class
 * @package Maxbond\Mueble
 */
class App
{
    /**
     * @var DBDriverInterface
     */
    public $dbDriver;
    /**
     * @var RepositoryFactory
     */
    public $repositoryFactory;

    /**
     * @var ViewInterface
     */
    public $template;

    /**
     * @var array
     */
    protected $dbClassMap;

    /**
     * @var array
     */
    protected $config;

    /**
     * App constructor.
     * @param array $config
     * @throws DBException
     * @throws UsageException
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->initDatabase();
        $this->repositoryFactory = new RepositoryFactory($config, $this->dbDriver);
        $this->initView();
    }

    /**
     * Get config key
     * @param string|null $key
     * @return array|mixed
     */
    public function config($key = null)
    {
        if ($key === null) {
            return $this->config;
        }

        return $this->config[$key];
    }

    /**
     * Call controller method from string
     * @param $path
     * @param $variables
     * @throws UsageException
     */
    public function callController($path, $variables)
    {
        $parts = explode('@', $path);
        if (!isset($parts[1]) || empty($parts[1])) {
            throw new UsageException('Invalid route path');
        }
        $class = $parts[0];
        $method = $parts[1];
        $object = new $class($this, $variables);
        $object->$method();
    }

    /**
     * @throws DBException
     * @throws UsageException
     */
    protected function initDatabase()
    {
        $this->dbClassMap = require('config/dbdriver.php');

        if (!array_key_exists('driver', $this->config)) {
            throw new UsageException('Specify database driver in config');
        }

        if (!array_key_exists($this->config['driver'], $this->dbClassMap)) {
            throw new UsageException('Database driver ' . $this->config['driver'] . ' does not exist');
        }

        try {
            $this->dbDriver = new $this->dbClassMap[$this->config['driver']](
                $this->config['host'],
                $this->config['username'],
                $this->config['password'],
                $this->config['database']
            );
        } catch (DBException $exception) {
            throw new DBException($exception->getMessage());
        }
    }

    /**
     * @throws UsageException
     */
    protected function initView()
    {
        if (!array_key_exists('view.engine', $this->config)) {
            throw new UsageException('Specify view templates engine');
        }

        if (!array_key_exists('view.templates', $this->config)) {
            throw new UsageException('Specify templates directory');
        }

        $this->template = ViewEngineFactory::get($this->config);
    }
}