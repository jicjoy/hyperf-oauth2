<?php

declare(strict_types=1);

namespace Hyperf\Oauth2\Repository;

use Hyperf\Oauth2\DBDriverInterface;
use Hyperf\Oauth2\Repository\Entity\DataObject;
class DBDriver implements DBDriverInterface
{

    public function __construct(private $connection, private $table,private $primaryKey = 'id')
    {

    }

    /**
     * 根据主键获取单条记录。
     *
     * @param mixed $id 主键值
     * @return mixed 返回查询到的记录，如果没有找到则返回 null
     */
    public function get($id):mixed
    {
        $model =  $this->connection->table($this->table)->where($this->primaryKey, $id)->first();

        if ($model) {
            return new DataObject($model instanceof \stdClass ? (array)$model :  $model->getAttributes());
        }
        return new DataObject();
    }

    /**
     * 保存数据到数据库表中。
     *
     * @param array $data 要插入的数据数组
     * @return mixed
     */
    public function save(array $data):mixed
    {
        return $this->connection->table($this->table)->insert($data);
    }

    /**
     * 更新指定ID的记录。
     *
     * @param array $where
     * @param array $data 包含要更新的字段及其新值的数组。
     * @return mixed
     */
    public function update(array $where,array $data):mixed
    {

      return  $this->connection->table($this->table)->where($where)->update($data);
    }
}
