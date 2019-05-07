<?php

namespace App\Repositories\Mysql;

use Maxbond\Mueble\Database\DBDriverInterface;

/**
 * Base class for repositories
 * @package App\Repositories
 */
abstract class BaseRepository
{
    /**
     * @var DBDriverInterface
     */
    protected $dBDRiver;

    /**
     * @var string
     */
    protected $table_name = '';

    /**
     * CategoriesRepository constructor.
     * @param DBDriverInterface $dBDriver
     */
    public function __construct(DBDriverInterface $dBDriver)
    {
        $this->dBDRiver = $dBDriver;
    }

    /**
     * Create record
     * @param $data
     */
    protected function create($data)
    {
        $fields = implode(',', array_keys($data));

        $binds = array_map(function ($a) {
            return ':' . $a;
        }, array_keys($data));

        $binds = implode(',',$binds);

        $sql = "INSERT INTO `{$this->table_name}` ({$fields}) VALUES ({$binds})";

        $this->dBDRiver->query($sql, $data);
    }

    /**
     * Update table by data
     * @param int $id
     * @param array $data
     */
    protected function update(int $id, array $data)
    {
        $sql = "UPDATE `{$this->table_name}` SET";
        foreach ($data as $name => $value) {
            $sql .= " {$name} = :{$name},";
        }
        $sql = rtrim($sql,',');
        $sql .= " WHERE id=:id";

        $this->dBDRiver->query($sql, array_merge(compact('id'),$data));
    }

    /**
     * Delete record
     * @param int $id
     */
    protected function delete(int $id)
    {
        $this->dBDRiver->query("DELETE FROM `{$this->table_name}` WHERE id=:id", compact('id'));
    }
}