<?php
/**
 * Author: yorks
 * Date: 01.04.2020
 */

namespace App;

require ('QueryBuilder.php');
require_once ('Where.php');

/**
 * Class Database
 * @package App
 */
abstract class Database extends Where
{

    /** @var null|QueryBuilder */
    public $builder;

    /**
     * Database constructor.
     * @param $host
     * @param $user
     * @param $password
     * @param $database
     */
    public function __construct($host, $user, $password, $database)
    {
        $this->connect($host, $user, $password, $database);
    }

    /** Connect to database
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $database
     * @return mixed
     */
    abstract protected function connect(string $host, string $user, string $password, string $database);

    /** Select data from database
     * @param string $fields
     * @return mixed
     */
    abstract public function select(string $fields = '*');

    /** Select one row from database
     * @param string $query
     * @return mixed
     */
    abstract public function one(string $query);

    /** Select row by primary key
     * @param string $query
     * @return mixed
     */
    abstract public function byPk(string $query);

    /** Select all data
     * @param string $query
     * @return mixed
     */
    abstract public function all(string $query);

    /** Get count of query
     * @param string $query
     * @return mixed
     */
    abstract public function count(string $query);

    /** Insert data into database
     * @param string $table
     * @param array $array
     * @return mixed
     */
    abstract public function insert(string $table, array $array);

    /** Update data
     * @param string $table
     * @param array $array
     * @param $where
     * @return bool
     */
    abstract public function update(string $table, array $array, $where): bool;

    /** Remove data from DB
     * @param string $table
     * @param $where
     * @return bool
     */
    abstract public function delete(string $table, $where): bool;

}
