<?php
/**
 * Author: yorks
 * Date: 01.04.2020
 */
namespace App;

require_once ('Database.php');


use PDOException;

/** PDO Database driver
 * Class PdoDB
 * @package App
 *
 */
abstract class PdoDB extends Database
{

    /** @var PDO|null  */
    public $connection;

    /**
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $database
     * @return bool|mixed
     */
    protected function connect($host, $user, $password, $database)
    {
        try {
            $this->connection = new \PDO("mysql:host={$host};dbname={$database}", $user, $password);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return true;
        }
        catch(PDOException $e)
        {
            throw new PDOException($e);
        }
    }

    /**
     * @param string $fields
     * @return QueryBuilder
     */
    public function select($fields = '*'): QueryBuilder
    {
        $this->builder = new QueryBuilder($fields, $this);
        return $this->builder;
    }

    /**
     * @param $query
     * @return array
     */
    public function query($query)
    {
        return $this->all($query);
    }

    /**
     * @param string $query
     * @return array
     */
    public function byPk($query)
    {
        return $this->one($query);
    }

    /**
     * @param string $query
     * @return mixed
     */
    public function one($query)
    {
        $stmt = $this->connection->query($query);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * @param string $query
     * @return array
     */
    public function all($query): array
    {
        $stmt = $this->connection->query($query);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * @param string $query
     * @return mixed
     */
    public function count($query)
    {
        $stmt = $this->connection->query($query);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * @param string $table
     * @param array $array
     * @return bool|mixed|string
     */
    public function insert(string $table, array $array)
    {
        $stmt = $this->connection->prepare("INSERT INTO {$table} " . $this->prepareInsertQuery($array));

        try{
            $stmt->execute($array);
            return $this->connection->lastInsertId();;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * @param string $table
     * @param array $array
     * @param $where
     * @return bool
     */
    public function update(string $table, $array, $where): bool
    {
        [$where, $where_values] = $this->executeWhere($where);
        $data = $this->prepareUpdateQuery($array);

        $array += $where_values;
        $stmt = $this->connection->prepare("UPDATE {$table} SET {$data} WHERE {$where}");

        try{
            $stmt->execute($array);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * @param string $table
     * @param $where
     * @return bool
     */
    public function delete(string $table, $where): bool
    {
        [$where, $where_values] = $this->executeWhere($where);
        $stmt = $this->connection->prepare("DELETE FROM {$table}  WHERE {$where}");

        try{
            $stmt->execute($where_values);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * @param $where
     * @return array
     */
    protected function executeWhere($where): array
    {
        $values = [];
        if (is_int($where)) {
            $values['id'] = $where;
            $where = "id = :id";
        } elseif (is_array($where)) {
            [$where, $values] = $this->prepareWhereQueryArray($where);
        } elseif (!is_string($where)) {
            throw new PDOException("Incorrect where data");
        }

        return [$where, $values];
    }

    /***
     * @param array $where
     * @return array
     */
    protected function prepareWhereQueryArray(array $where): array
    {
        $return = [];
        $values = [];
        $i = 0;
        foreach ($where as $key => $value) {
            if (is_array($value)) {
                $condition = $value[1] ?? '=';
                $or_and = $i > 0 ? ($value[2] ?? 'AND') : '';
                $return[] = "{$or_and} {$key} {$condition} :{$key}";
                $values[$key] = $value[0];
            } else {
                $return[] = "{$key} = :{$key}";
                $values[$key] = $value;
            }
            $i++;
        }

        return [implode(',', $return), $values];
    }

    /**
     * @param array $values
     * @return string
     */
    protected function prepareInsertQuery(array $values): string
    {
        $keys = [];
        $vals = [];
        foreach ($values as $key => $value) {
            $keys[] = $key;
            $vals[] = ":{$key}";
        }
        $key_string = implode(',', $keys);
        $value_string = implode(',', $vals);
        return "({$key_string}) VALUES ($value_string)";
    }

    /**
     * @param array $values
     * @return string
     */
    protected function prepareUpdateQuery(array $values): string
    {
        $return = [];
        foreach ($values as $key => $value) {
            $return[] = "{$key} = :{$key}";
        }

        return implode(',', $return);
    }
}
