<?php

namespace Maxbond\Mueble\Http;

use Maxbond\Mueble\App;

/**
 * Base controller class
 * @package Maxbond\Mueble\Http
 */
abstract class Controller
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    const METHOD_PATCH = 'PATCH';
    const METHOD_HEAD = 'HEAD';

    /**
     * @var mixed
     */
    protected $method;

    /**
     * @var App
     */
    protected $app;

    /**
     * @var array|null
     */
    protected $variables;

    /**
     * Controller constructor.
     * @param $app
     * @param $variables
     */
    public function __construct($app, ?array $variables = [])
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? static::METHOD_GET;
        $this->app = $app;
        $this->variables = $variables;
    }

    /**
     * @param string $name
     * @param null $default
     * @return mixed|null
     */
    protected function get(string $name, $default = null)
    {
        return $_GET[$name] ?? $default;
    }

    /**
     * @param string $name
     * @param null $default
     * @return mixed|null
     */
    protected function post(string $name, $default = null)
    {
        return $_POST[$name] ?? $default;
    }

    /**
     * Populate values array from request, POST first
     *
     * @param array $fields
     * @return array
     */
    protected function input(array $fields): array
    {
        $values = [];
        foreach ($fields as $field) {
            $values[$field] = $this->method === static::METHOD_GET ? $this->get($field) : $this->post($field);
        }

        return $values;
    }

    /**
     * Show error page
     * @param $code
     */
    protected function error($code)
    {
        switch ($code) {
            case 404:
                $this->render('errors/404.twig', [], 404);
                break;
        }
    }

    /**
     * Render and output view
     * @param $template
     * @param $variables
     * @param int $code
     */
    protected function render($template, $variables, $code=200)
    {
        http_response_code($code);
        ob_start();
        echo $this->app->template->view($template,$variables);
        ob_end_flush();
    }
}