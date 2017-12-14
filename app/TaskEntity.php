<?php
/**
 * Created by PhpStorm.
 * User: mrcake
 * Date: 12/14/17
 * Time: 2:36 AM
 */

namespace app;


class TaskEntity
{
    const TASK_STATUS_NEW = 1;
    const TASK_STATUS_DONE = 2;

    protected $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public function __get($name)
    {
        return $this->getField($name, false);
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getField(string $name, $strict = false)
    {
        $isset = isset($this->$name);

        if (!$isset && $strict) {
            throw new \InvalidArgumentException(sprintf("Entity has not `%s` field", $name));
        }

        return $this->fields[$name] ?? null;
    }

    public function setField(string $name, $value)
    {
        $this->fields[$name] = $value;
    }

    public function getIsDone()
    {
        return $this->getField('task_status') == self::TASK_STATUS_DONE;
    }
}