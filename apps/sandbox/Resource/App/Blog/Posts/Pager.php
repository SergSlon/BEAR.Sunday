<?php
namespace Sandbox\Resource\App\Blog\Posts;

use BEAR\Sunday\Annotation\Db;
use BEAR\Sunday\Annotation\Cache;
use BEAR\Sunday\Interceptor\DbSetterInterface;
use BEAR\Sunday\Annotation\DbPager;
use PDO;

use Sandbox\Resource\App\Blog\Posts;

/**
 * Posts
 *
 * @Db
 */
class Pager extends Posts implements DbSetterInterface
{

    /**
     * Get
     *
     * @Cache
     * @DbPager(2)
     *
     * @return array
     */
    public function onGet($id = null)
    {
        $sql = "SELECT id, title, body, created, modified FROM {$this->table}";
        if (is_null($id)) {
            $stmt = $this->db->query($sql);
            $this->body = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $sql .= " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->execute();
            $this->body = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return $this;
    }
}
