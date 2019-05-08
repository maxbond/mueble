<?php

namespace App\Repositories\Mysql;

use App\Repositories\CategoriesInterface;

/**
 * MySQL repository for categories
 * @package App\Repositories\Mysql
 */
class CategoriesRepository extends BaseRepository implements CategoriesInterface
{

    protected $table_name = 'categories';

    /**
     * @return array
     */
    public function getCategories(): array
    {
        return $this->dBDRiver->select("SELECT cc.*,pc.name AS parent_name FROM `{$this->table_name}` cc 
                        LEFT JOIN `{$this->table_name}` pc ON cc.parent_id = pc.id");
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getCategory(int $id)
    {
        $category =$this->dBDRiver->select("SELECT cc.*,pc.name AS parent_name FROM `{$this->table_name}` cc 
                        LEFT JOIN `{$this->table_name}` pc ON cc.parent_id = pc.id WHERE cc.id=:id",
            compact('id'));

        return $category[0] ?? null;
    }

    /**
     * @param int $parent_id
     * @return array
     */
    public function getChildren(int $parent_id): array
    {
        return $this->dBDRiver->select("SELECT * FROM `{$this->table_name}` 
                    WHERE `parent_id` = :parent_id",compact('parent_id'));
    }

    /**
     * @return array
     */
    public function getTopLevel(): array
    {
        return $this->dBDRiver->select("SELECT * FROM `{$this->table_name}` WHERE `parent_id` IS NULL");
    }

    /**
     * @param array $data
     * @return mixed|void
     */
    public function createCategory(array $data)
    {
        parent::create($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return mixed|void
     */
    public function updateCategory(int $id, array $data)
    {
        parent::update($id,$data);
    }

    /**
     * @param int $id
     * @return mixed|void
     */
    public function deleteCategory(int $id)
    {
        parent::delete($id);
    }

}