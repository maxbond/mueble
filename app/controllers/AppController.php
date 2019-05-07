<?php

namespace App\Controllers;

use Maxbond\Mueble\Http\Controller;
use App\Repositories\CategoriesInterface;
use App\Repositories\ItemsInterface;

class AppController extends Controller
{
    /**
     * @var ItemsInterface
     */
    protected $itemsRepository;

    /**
     * @var CategoriesInterface
     */
    protected $categoriesRepository;

    /**
     * AppController constructor.
     * @param $app
     * @param $variables
     */
    public function __construct($app, ?array $variables = [])
    {
        parent::__construct($app, $variables);
        $this->itemsRepository = $this->app->repositoryFactory->get('items');
        $this->categoriesRepository = $this->app->repositoryFactory->get('categories');
    }

    /**
     * Main page
     */
    public function index()
    {
        $categories = $this->categoriesRepository->getTopLevel();
        $this->render('home.twig', compact('categories'));
    }
}