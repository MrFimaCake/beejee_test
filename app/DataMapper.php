<?php
/**
 * Created by PhpStorm.
 * User: mrcake
 * Date: 12/14/17
 * Time: 1:45 AM
 */

namespace app;

use PDO;

class DataMapper
{
    private static $connection;

    public function __construct(PDO $connection)
    {
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /** @var PDO connection */
        self::$connection = $connection;

        $this->checkTables();
    }

    public function getFromTable(string $table, array $fields, array $findOptions) : \PDOStatement
    {
        $queryFields = implode(', ', $fields);
        $queryString = sprintf(
            "SELECT %s FROM %s "
            ." ORDER BY %s %s "
            ." LIMIT %s OFFSET %s",
            $queryFields,
            $table,
            $findOptions['sort_field'],
            $findOptions['sort_value'],
            $findOptions['limit'],
            $findOptions['offset']
        );

        return self::$connection->query($queryString);
    }

    public function getCount($table)
    {
        $queryString = "SELECT COUNT(id) AS count_items FROM %s";
        $query = self::$connection->query(sprintf($queryString, $table));
        return $query;
    }

    public function update($table, $id, $fields)
    {
        $fieldKeysInQuery = [];

        foreach ($fields as $fieldKey => $field) {
            if (!is_null($field)) {
                $fieldKeysInQuery[] = $fieldKey . " = '{$field}'";
            }
        }

        $queryString = "UPDATE %s SET %s WHERE id = %d";
        $queryString = sprintf(
            $queryString,
            $table,
            implode(',', $fieldKeysInQuery),
            $id
        );

        $prepared = self::$connection->prepare($queryString);

        if ($prepared) {
            if ($prepared->execute()) {
                return $this->getById($table, $id, array_keys($fields));
            }
        }
    }

    public function create($table, $fields)
    {
        $fieldKeys = array_keys($fields);
        $fieldKeysInQuery = array_map(function($field){ return ':'.$field; }, $fieldKeys);

        $queryString = "INSERT INTO %s (%s) VALUES(%s)";
        $queryString = sprintf(
            $queryString,
            $table,
            implode(',',$fieldKeys),
            implode(',',$fieldKeysInQuery)
        );

        $prepared = self::$connection->prepare($queryString);

        for ($i = 0; $i < count($fields); $i++) {
            $prepared->bindValue($fieldKeysInQuery[$i], $fields[$fieldKeys[$i]]);
        }

        $executed = $prepared->execute();

        $lastId = self::$connection->lastInsertId();

        return $this->getById($table, $lastId, $fieldKeys);
    }

    public function remove($table, $id)
    {
        $queryString = "DELETE FROM %s WHERE id = %d";
        $queryString = sprintf(
            $queryString,
            $table,
            $id
        );
        return self::$connection->exec($queryString);
    }

    public function getById($table, $id, $fields)
    {
//        $queryFields = implode(',', array_keys($fields));
        $queryFields = implode(',', $fields);
        $queryString = sprintf("SELECT %s FROM %s WHERE `id` = %d", $queryFields, $table, $id);

        return $this->getFromStatement(self::$connection->query($queryString), true);
    }

    protected function getFromStatement(\PDOStatement $statement, $first= true)
    {
        $result = [];
        foreach ($statement as $item) {
            $result[] = $item;
        }
        return $first ? array_shift($result) : $result;
    }

    protected function checkTables()
    {
//        self::$connection->exec("DROP TABLE tasks");
        self::$connection->exec("CREATE TABLE IF NOT EXISTS tasks(
            id INTEGER  PRIMARY KEY,
            username VARCHAR(70) NOT NULL,
            email VARCHAR(70) NOT NULL,
            task_body TEXT,
            image_path VARCHAR (255),
            task_status SMALLINT (1)
        )");
    }
}
