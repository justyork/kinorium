<?php
/**
 * Author: yorks
 * Date: 01.04.2020
 */

namespace App;
require_once ('Where.php');


/**
 * Class QueryBuilder
 * @package App
 */
class QueryBuilder extends Where
{
    protected $query = [];
    protected $parent;

    /**
     * QueryBuilder constructor.
     * @param $select
     * @param Database $parent
     */
    public function __construct($select, Database $parent)
    {
        $this->parent = $parent;
        $this->query['select'] = $select;
    }

    /**
     * @param $table
     * @return QueryBuilder
     */
    public function from($table): QueryBuilder
    {
        $this->query['table'] = $table;
        return $this;
    }

    /**
     * @param $where
     * @return QueryBuilder
     */
    public function where($where): QueryBuilder
    {
        $this->query['where'] = $where;
        return $this;
    }

    /**
     * @param $limit
     * @return QueryBuilder
     */
    public function limit($limit): QueryBuilder
    {
        $this->query['limit'] = $limit;
        return $this;
    }

    /**
     * @param $offset
     * @return QueryBuilder
     */
    public function offset($offset): QueryBuilder
    {
        $this->query['offset'] = $offset;
        return $this;
    }

    /**
     * @param $order
     * @return QueryBuilder
     */
    public function orderBy($order): QueryBuilder
    {
        $this->query['order'] = $order;
        return $this;
    }

    /**
     * @param $group
     * @return QueryBuilder
     */
    public function groupBy($group): QueryBuilder
    {
        $this->query['group'] = $group;
        return  $this;
    }

    /** Join inner
     * @param $table
     * @param $on
     * @return QueryBuilder
     */
    public function join($table, $on): QueryBuilder
    {
        return  $this->joinInner($table, $on);
    }

    /** Joint left
     * @param $table
     * @param $on
     * @return QueryBuilder
     */
    public function joinLeft($table, $on): QueryBuilder
    {
        $this->query['join'] = ['LEFT', $table, $on];
        return  $this;
    }

    /** Join right
     * @param $table
     * @param $on
     * @return QueryBuilder
     */
    public function joinRight($table, $on): QueryBuilder
    {
        $this->query['join'] = ['RIGHT', $table, $on];
        return  $this;
    }

    /** Join inner
     * @param $table
     * @param $on
     * @return QueryBuilder
     */
    public function joinInner($table, $on): QueryBuilder
    {
        $this->query['join'] = ['INNER', $table, $on];
        return  $this;
    }

    /** Join outer
     * @param $table
     * @param $on
     * @return QueryBuilder
     */
    public function joinOuter($table, $on): QueryBuilder
    {
        $this->query['join'] = ['OUTER', $table, $on];
        return  $this;
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function buildQuery(): string
    {
        if (!isset($this->query['table'])) {
            throw new \Exception('You need to select table');
        }

        $query = 'SELECT ' . $this->query['select'];
        $query .= ' FROM '.$this->query['table'];

        if (isset($this->query['join'])) {
            $join = $this->query['join'];
            $query .= " JOIN {$join[0]} {$join[1]} ON {$join[2]}";
        }
        if (isset($this->query['where']))
            $query .= ' WHERE '.$this->executeWhere($this->query['where']);
        if (isset($this->query['order']))
            $query .= ' ORDER BY '.$this->query['order'];
        if (isset($this->query['group']))
            $query .= ' GROUP BY '.$this->query['group'];
        if (isset($this->query['limit']))
            $query .= ' LIMIT '.$this->query['limit'];
        if (isset($this->query['offset']))
            $query .= ' OFFSET '.$this->query['offset'];

        return $query;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function one()
    {
        $query = $this->buildQuery();
        return $this->parent->one($query);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function all()
    {
        $query = $this->buildQuery();
        return $this->parent->all($query);
    }

    /**
     * @param $id
     * @param string $pk
     * @return mixed
     * @throws \Exception
     */
    public function byPk($id, $pk = 'id')
    {
        $this->query['id'] = $id;
        $this->query['pk_name'] = $pk;
        $query = $this->buildQuery();
        return $this->parent->byPk($query);
    }

}
