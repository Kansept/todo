<?php

namespace App\Service\Db;

class Db
{
    private $connection;

    public function __construct(\mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function exec($sql, array $param = [])
    {
        $stmt = $this->connection->prepare($sql);
        if (!empty($this->connection->error)) {
            throw new DbException($this->connection->error);
        }
        $types = '';
        $values = [];
        foreach ($param as $field => $value) {
            if (is_int($value)) {
                $types .= 'i';
            } else if (is_double($value)) {
                $types .= 'd';
            } else if (is_string($value)) {
                $types .= 's';
            } else {
                $types .= 'b';
            }
            $values[] = &$param[$field];
        }
        if (!empty($param)) {
            call_user_func_array(array($stmt, "bind_param"), array_merge(array($types), $values));
        }
        $status = $stmt->execute();
        $query = $stmt->get_result();

        if ($query instanceof \mysqli_result) {
            $data = [];
            while ($row = $query->fetch_assoc()) {
                $data[] = $row;
            }
            $result = new \stdClass();
            $result->num_rows = $query->num_rows;
            $result->row = isset($data[0]) ? $data[0] : array();
            $result->rows = $data;
            $stmt->close();

            return $result;
        } else {
            return $status;
        }
    }

    public function escape($value)
    {
        return $this->connection->real_escape_string($value);
    }

    public function countAffected()
    {
        return $this->connection->affected_rows;
    }

    public function getLastId()
    {
        return $this->connection->insert_id;
    }

    public function isConnected()
    {
        return $this->connection->ping();
    }

    public function error()
    {
        return $this->error;
    }

    public function setCharset($charset)
    {
        $this->connection->set_charset($charset);
    }

    /**
     * Вставка данных в таблицу
     *
     * @param string $db_name
     * @param string $table
     * @param array $data
     * @return bool
     */
    public function insert($table, $data)
    {
        $keys = '`' . implode('`, `', array_keys($data)) . '`';

        $values = array_map(
            function ($value) {
                return '?';
            },
            $data
        );
        $values = implode(',', $values);

        $sql = "INSERT INTO `{$table}` ({$keys}) VALUES ({$values})";

        return $this->exec($sql, array_values($data));
    }

    /**
     * Обновление строк в бд
     *
     * @param string $db_name
     * @param string $table
     * @param array $data
     * @param array $where
     * @return bool
     */
    public function update($table, array $data, array $where)
    {
        $keys = array_map(
            function ($value) {
                return "`$value` = ?";
            },
            array_keys($data)
        );
        $key_str = implode(',', $keys);

        $where_keys = array_map(
            function ($value) {
                return "`$value` = ?";
            },
            array_keys($where)
        );
        $where_str = \implode(' AND ', $where_keys);

        $param = array_merge(array_values($data), array_values($where));
        $sql = "UPDATE `{$table}` SET {$key_str} WHERE {$where_str}";

        return $this->exec($sql, $param);
    }

    /**
     * Удаление
     *
     * @param string $table
     * @param array $where
     * @return bool
     */
    public function delete($table, array $where)
    {
        $where_keys = array_map(
            function ($value) {
                return "`$value` = ?";
            },
            array_keys($where)
        );
        $where_str = \implode(' AND ', $where_keys);

        $param = array_values($where);
        $sql = "DELETE FROM `{$table}` WHERE {$where_str}";

        return $this->exec($sql, $param);
    }
}
