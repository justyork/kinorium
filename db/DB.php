<?php
/**
 * Author: yorks
 * Date: 01.04.2020
 */

namespace App;

require ('PdoDB.php');

class DB extends PdoDB
{
    /**
     * DB constructor.
     * @param $host
     * @param $user
     * @param $password
     * @param $database
     * TODO передавать последним параметром тип используемого драйвера
     */
    public function __construct($host, $user, $password, $database)
    {
        parent::__construct($host, $user, $password, $database);
    }
}
