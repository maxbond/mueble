<?php

namespace App\Repositories;

interface ItemsInterface
{
    /**
     * Get all items
     * @return array
     */
    public function getItems() : array;

    /**
     * Get one item
     * @param int $id
     * @return array
     */
    public function getItem(int $id);

    /**
     * Get all items in category
     * @param int $category_id
     * @return array
     */
    public function getItemsInCategory(int $category_id) : array;

    /**
     * Create single item
     * @param array $data
     * @return mixed
     */
    public function createItem(array $data);

    /**
     * Update single item
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateItem(int $id, array $data);

    /**
     * Delete item
     * @param int $id
     * @return mixed
     */
    public function deleteItem(int $id);
}