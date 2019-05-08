<?php

namespace App\Controllers\Admin;

use Maxbond\Mueble\Http\Controller;
use App\Repositories\CategoriesInterface;
use App\Repositories\ItemsInterface;

class AdminCategoriesController extends Controller
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
        $categories = $this->categoriesRepository->getCategories();
        $this->render('admin/categories/list.twig', compact('categories'));
    }

    /**
     * new category form
     */
    public function create()
    {
        $categories = $this->categoriesRepository->getTopLevel();
        $this->render('admin/categories/create.twig', compact('categories'));
    }

    /**
     * store category
     */
    public function store()
    {
        $input = $this->input(['name','parent_id']);

        if ($input['parent_id'] === '' || !$input['parent_id']){
            unset($input['parent_id']);
        }

        $this->categoriesRepository->createCategory($input);
        $this->redirect('/admin/categories');
    }

    /**
     * edit category form
     */
    public function edit()
    {
        $category = $this->categoriesRepository->getCategory((int) $this->variables['id']);
        $categories = $this->categoriesRepository->getTopLevel();
        $this->render('admin/categories/edit.twig', compact('category','categories'));
    }

    /**
     * update category
     */
    public function update()
    {
        $input = $this->input(['name','parent_id']);

        if ($input['parent_id'] === '' || !$input['parent_id']){
            unset($input['parent_id']);
        }

        $this->categoriesRepository->updateCategory((int) $this->variables['id'],$input);
        $this->redirect('/admin/categories');
    }

    /**
     * delete category
     */
    public function delete()
    {
        $this->categoriesRepository->deleteCategory((int) $this->variables['id']);
        $this->redirect('/admin/categories');
    }
}