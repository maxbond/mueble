<?php

namespace App\Repositories\Mysql;

use App\Repositories\ItemsInterface;

/**
 * MySQL repository for items
 * @package App\Repositories\Mysql
 */
class ItemsRepository extends BaseRepository implements ItemsInterface
{
    protected $table_name = 'items';

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->dBDRiver->select("SELECT * FROM `{$this->table_name}`");
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getItem(int $id)
    {
        $item = $this->dBDRiver->select("SELECT * FROM `{$this->table_name}` WHERE id=:id",
            compact('id'));

        return $item[0] ?? null;
    }

    /**
     * @param int $category_id
     * @return array
     */
    public function getItemsInCategory(int $category_id): array
    {
        return $this->dBDRiver->select("SELECT * FROM `{$this->table_name}` WHERE `category_id`=:category_id",
            compact('category_id'));
    }

    /**
     * @param array $data
     * @return mixed|void
     */
    public function createItem(array $data)
    {
        parent::create($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return mixed|void
     */
    public function updateItem(int $id, array $data)
    {
        parent::update($id, $data);
    }

    /**
     * @param int $id
     * @return mixed|void
     */
    public function deleteItem(int $id)
    {
        parent::delete($id);
    }

}