<?php

namespace App\Repositories;

interface CategoriesInterface
{
    /**
     * Get all categories
     * @return array
     */
    public function getCategories() : array;

    /**
     * Get single category
     * @param int $id
     * @return mixed
     */
    public function getCategory(int $id);

    /**
     * Get all children categories
     * @param int $parent_id
     * @return array
     */
    public function getChildren(int $parent_id) : array;

    /**
     * Get all top-level categories
     * @return array
     */
    public function getTopLevel() : array;

    /**
     * Create category
     * @param array $data
     * @return mixed
     */
    public function createCategory(array $data);

    /**
     * Update category
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateCategory(int $id, array $data);

    /**
     * Delete category
     * @param int $id
     * @return mixed
     */
    public function deleteCategory(int $id);
}