<?php

namespace App\Controllers\Admin;

use Maxbond\Mueble\Http\Controller;
use App\Repositories\CategoriesInterface;
use App\Repositories\ItemsInterface;

class AdminItemsController extends Controller
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
        $items = $this->itemsRepository->getItems();
        $this->render('admin/items/list.twig', compact('items'));
    }

    /**
     * new category form
     */
    public function create()
    {
        $categories = $this->categoriesRepository->getCategories();
        $this->render('admin/items/create.twig', compact('categories'));
    }

    /**
     * store category
     */
    public function store()
    {
        $input = $this->input(['name','category_id','info','photo','price']);
        $this->itemsRepository->createItem($input);
        $this->redirect('/admin/items');
    }

    /**
     * edit category form
     */
    public function edit()
    {
        $categories = $this->categoriesRepository->getCategories();
        $item = $this->itemsRepository->getItem((int) $this->variables['id']);
        $this->render('admin/items/edit.twig', compact('categories','item'));
    }

    /**
     * update category
     */
    public function update()
    {
        $input = $this->input(['name','category_id','info','photo','price']);
        $this->itemsRepository->updateItem((int) $this->variables['id'],$input);
        $this->redirect('/admin/items');
    }

    /**
     * delete category
     */
    public function delete()
    {
        $this->itemsRepository->deleteItem((int) $this->variables['id']);
        $this->redirect('/admin/items');
    }
}