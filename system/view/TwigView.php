<?php

namespace Maxbond\Mueble\View;

use Maxbond\Mueble\Interfaces\ViewInterface;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

/**
 * Implement Twig template engine
 * @package Maxbond\Mueble\View
 */
class TwigView implements ViewInterface
{
    public $twig;

    /**
     * TwigView constructor.
     * @param string $templates
     * @param string $cache
     */
    public function __construct(string $templates, $cache)
    {
        $loader = new FilesystemLoader($templates);
        $this->twig = new Environment($loader, [
           'cache' => $cache,
        ]);
    }

    /**
     * @param string $view
     * @return \Twig\TemplateWrapper|\Twig_TemplateWrapper
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function load(string $view)
    {
        return $this->twig->load($view);
    }

    /**
     * @param $template
     * @param array $variables
     * @return mixed
     */
    public function render($template, ?array $variables=[])
    {
        return $template->render($variables);
    }

    /**
     * @param $view
     * @param array $variables
     * @return mixed
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function view($view, ?array $variables=[])
    {
        $template = $this->load($view);

        return $this->render($template,$variables);
    }

}