<?php
/**
 * Database Abstraction Layer
 */
class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $this->pdo = DB;
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Выполнение запроса
     */
    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Получение одного редктора
     */
    public function one($sql, $params = [])
    {
        return $this->query($sql, $params)->fetch();
    }

    /**
     * Получение всех редкторов
     */
    public function all($sql, $params = [])
    {
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * Вставка
     */
    public function insert($table, $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        return $this->query($sql, $data);
    }

    /**
     * Обновление
     */
    public function update($table, $data, $where, $params = [])
    {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "{$key} = :{$key}";
            $params[":{$key}"] = $value;
        }
        
        $set_string = implode(', ', $set);
        $sql = "UPDATE {$table} SET {$set_string} WHERE {$where}";
        
        return $this->query($sql, $params);
    }

    /**
     * Удаление
     */
    public function delete($table, $where, $params = [])
    {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        return $this->query($sql, $params);
    }

    /**
     * Получение количества строк
     */
    public function count($table, $where = '1', $params = [])
    {
        $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$where}";
        $result = $this->one($sql, $params);
        return (int)$result['count'];
    }

    /**
     * Получение ID последнего вставленного редктора
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
?>
