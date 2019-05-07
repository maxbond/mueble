<?php

namespace Maxbond\Mueble\Interfaces;

/**
 * Common view class methods
 * @package Maxbond\Mueble\Interfaces
 */
interface ViewInterface
{
    /**
     * Load view into template class
     * @param string $view
     * @return mixed
     */
    public function load(string $view);

    /**
     * Render template with variables bind
     * @param $template
     * @param array $variables
     * @return mixed
     */
    public function render($template, ?array $variables);

    /**
     * Load view and return rendered
     * @param $view
     * @param array $variables
     * @return mixed
     */
    public function view($view,?array $variables);
}