<?php

namespace App\Controllers;

use Maxbond\Mueble\Http\Controller;
use App\Repositories\CategoriesInterface;
use App\Repositories\ItemsInterface;

class ItemController extends Controller
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


    public function item()
    {
        $id = (int)$this->variables['id'];
        $item = $this->itemsRepository->getItem($id);
        if (!$item) {
            $this->error(404);
        }
        $category = $this->categoriesRepository->getCategory($item->category_id);
        $this->render('items/index.twig', compact('item','category'));
    }
}