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

    /**
     * Add item to basket
     */
    public function item()
    {
        $basket = $_SESSION['basket'] ?? [];
        $basket[] = (int) $this->variables['id'];
        $_SESSION['basket'] = $basket;
        $item = $this->itemsRepository->getItem((int) $this->variables['id']);
        $this->render('items/index.twig',compact('item'));
    }

    /**
     * Basket
     */
    public function basket()
    {
        $basket = $_SESSION['basket'] ?? [];
        if(count($basket)){
            $items = $this->itemsRepository->getItemsInList($basket);
            $this->render('items/basket.twig', compact('items'));
        }else{
            $this->render('items/empty_basket.twig', compact('items'));
        }

    }

    /**
     * Remove item from basket
     */
    public function remove()
    {
        $basket = $_SESSION['basket'] ?? [];
        $basket = array_diff($basket,[(int) $this->variables['id']]);
        $_SESSION['basket'] = $basket;
        $this->redirect('/basket');
    }

    /**
     * Complete order
     */
    public function done()
    {
        session_destroy();
        $this->render('items/done.twig',[]);
    }
}