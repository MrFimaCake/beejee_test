<?php
/**
 * Created by PhpStorm.
 * User: mrcake
 * Date: 12/14/17
 * Time: 2:09 AM
 */

namespace app;


class TaskRepository
{
    /** @var DataMapper $dataMapper */
    protected $dataMapper;

    protected $table = 'tasks';

    public function __construct(DataMapper $dataMapper)
    {
        $this->dataMapper = $dataMapper;
    }

    public function getEntityFields()
    {
        return ['id', 'email', 'username', 'task_body', 'task_status', 'image_path'];
    }

    protected function rowToFields(array $item)
    {
        $resultItem = [];
        foreach ($this->getEntityFields() as $field) {
            $resultItem[$field] = $item[$field];
        }
        return $resultItem;
    }

    /**
     * @param $findOptions
     * @return array
     */
    public function getEntities($findOptions)
    {
        $statement = $this->dataMapper->getFromTable($this->table, $this->getEntityFields(), $findOptions);
        $res = [];

        foreach ($statement as $item) {
            $resultItem = $this->rowToFields($item);
            $res[] = new TaskEntity($resultItem);
        }

        return $res;
    }

    /**
     * @param array $findOptions
     * @return array
     */
    public function getAll($findOptions = [])
    {
        $findOptions = array_merge(
            $this->getDefaultFindOptions(),
            array_filter($findOptions)
        );
        return $this->getEntities($findOptions);
    }

    public function getCount()
    {
        $statement = $this->dataMapper->getCount($this->table);
        foreach ($statement as $item) {
            return $item['count_items'];
        }
        return false;
    }

    /**
     * @param $id
     * @return TaskEntity
     */
    public function getById($id) : TaskEntity
    {
        $row = $this->dataMapper->getById($this->table, $id, $this->getEntityFields());
        $fields = $this->rowToFields($row);
        return new TaskEntity($fields);
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        return $this->dataMapper->remove($this->table, $id);
    }

    /**
     * @param $id
     * @param $fields
     * @return TaskEntity
     */
    public function updateTask($id, $fields)
    {
        $fieldsToSave = [];
        foreach ($this->getEntityFields() as $fieldKey) {
            $fieldsToSave[$fieldKey] = $fields[$fieldKey] ?? null;
        }

        $updated = $this->dataMapper->update($this->table, $id, $fieldsToSave);
        $resultItem = $this->rowToFields($updated);

        return new TaskEntity($resultItem);
    }

    /**
     * @param $fields
     * @return TaskEntity
     */
    public function createTask($fields)
    {
        $fieldsToSave = [];
        foreach ($this->getEntityFields() as $fieldKey) {
            $fieldsToSave[$fieldKey] = $fields[$fieldKey] ?? null;
        }
        $created = $this->dataMapper->create($this->table, $fieldsToSave);
        $resultItem = $this->rowToFields($created);

        return new TaskEntity($resultItem);
    }

    /**
     * @return array
     */
    protected function getDefaultFindOptions()
    {
        return [
            'limit' => 3,
            'offset' => 0,
            'sort_field' => 'id',
            'sort_value' => 'DESC'
        ];
    }
}