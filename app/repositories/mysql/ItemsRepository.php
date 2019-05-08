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
        return $this->dBDRiver->select("SELECT ii.*,cc.name as category FROM `{$this->table_name}` ii
                    LEFT JOIN `categories` cc ON ii.category_id=cc.id");
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getItem(int $id)
    {
        $item = $this->dBDRiver->select("SELECT ii.*,cc.name as category FROM `{$this->table_name}` ii
                    LEFT JOIN `categories` cc ON ii.category_id=cc.id WHERE ii.id=:id",
            compact('id'));

        return $item[0] ?? null;
    }

    /**
     * @param int $category_id
     * @return array
     */
    public function getItemsInCategory(int $category_id): array
    {
        return $this->dBDRiver->select("SELECT ii.*,cc.name as category FROM `{$this->table_name}` ii
                    LEFT JOIN `categories` cc ON ii.category_id=cc.id WHERE `category_id`=:category_id",
            compact('category_id'));
    }


    /**
     * @param int $category_id
     * @return array
     */
    public function getItemsInTopCategory(int $category_id): array
    {
        return $this->dBDRiver->select("SELECT ii.*,cc.name as category FROM `{$this->table_name}` ii
                    LEFT JOIN `categories` cc ON ii.category_id=cc.id 
                    WHERE ii.category_id IN (SELECT id FROM `categories` WHERE parent_id=:category_id)",compact('category_id'));
    }

    /**
     * @param array $list
     * @return array
     */
    public function getItemsInList(array $list): array
    {
        $items = implode(',',$list);

        return $this->dBDRiver->select("SELECT ii.*,cc.name as category FROM `{$this->table_name}` ii
                    LEFT JOIN `categories` cc ON ii.category_id=cc.id 
                    WHERE ii.id IN ({$items})");

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