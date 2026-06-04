<?php
/**
 * Database - класс для работы с БД
 */
class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $this->pdo = DB;
    }

    /**
     * Получение singleton экземпляра
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Выполнение SELECT запроса с одной строкой результата
     */
    public function one($query, $params = [])
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    /**
     * Выполнение SELECT запроса со всеми строками результата
     */
    public function all($query, $params = [])
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Подсчёт количества строк
     */
    public function count($table, $where = '')
    {
        $query = "SELECT COUNT(*) as count FROM $table";
        if (!empty($where)) {
            $query .= " WHERE $where";
        }
        $result = $this->one($query);
        return $result['count'] ?? 0;
    }

    /**
     * INSERT
     */
    public function insert($table, $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute(array_values($data));
    }

    /**
     * UPDATE
     */
    public function update($table, $data, $where, $params = [])
    {
        $set = implode(', ', array_map(fn($k) => "$k = ?", array_keys($data)));
        $query = "UPDATE $table SET $set WHERE $where";
        $values = array_merge(array_values($data), $params);
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($values);
    }

    /**
     * DELETE
     */
    public function delete($table, $where, $params = [])
    {
        $query = "DELETE FROM $table WHERE $where";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * ID последней вставки
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Начало транзакции
     */
    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Коммит транзакции
     */
    public function commit()
    {
        return $this->pdo->commit();
    }

    /**
     * Откат транзакции
     */
    public function rollBack()
    {
        return $this->pdo->rollBack();
    }

    /**
     * Прямое выполнение запроса
     */
    public function exec($query)
    {
        return $this->pdo->exec($query);
    }
}
?>