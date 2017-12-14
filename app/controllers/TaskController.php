<?php
/**
 * Created by PhpStorm.
 * User: mrcake
 * Date: 12/14/17
 * Time: 12:52 AM
 */

namespace app\controllers;


use app\exceptions\ValidationException;
use app\utils\Paginator;
use app\TaskRepository;

class TaskController extends Controller
{
    public function index()
    {
        $repo = new TaskRepository($this->dataMapper);


        $limit = $this->getParam('limit', 3);

        $sortField = $this->getParam('sort-type', 'id');
        $sortValue = $this->getParam('sort-value', 'desc');

        $page = $this->getParam('page', 1);

        $list = $repo->getAll([
            'offset' => ($page - 1) * $limit,
            'sort_field' => $sortField,
            'sort_value' => $sortValue
        ]);

        $count = $repo->getCount();

        return $this->render('list.php', [
            'list' => $list,
            'paginator' => new Paginator($this->getRequest()->getUri(), ceil($count / $limit), $page)
        ]);
    }

    public function update()
    {
        $repo = new TaskRepository($this->dataMapper);

        $entity = $repo->getById($this->getParam('id', null));

        return $this->render('update_task_form.php', ['model' => $entity]);
    }

    public function updateSave()
    {
        $repo = new TaskRepository($this->dataMapper);

        $error = null;

        $fields = $this->getParams();

//        echo '<pre>';die(var_dump(__METHOD__, $fields));

        if ($newFile = $this->getRequest()->loadFile('task_file')) {
            $fields['image_path'] = $newFile;
        }


        $entity = $repo->updateTask($this->getParam('id', false), $fields);

        if (!$entity) {
            return $this->render('update_task_form.php', ['model' => $entity]);
        }

        return $this->redirect('/');
    }

    public function create()
    {
        return $this->render('add_task_form.php');
    }

    public function createSave()
    {
        try {

            $repo = new TaskRepository($this->dataMapper);

            $error = null;

            $fields = $this->getParams();
            $fields['image_path'] = $this->getRequest()->loadFile('task_file');

            $entity = $repo->createTask($fields);

            if ($entity !== null) {
                return $this->redirect('/tasks');
            }

        } catch (ValidationException $e){
            $error = $e->getMessage();
        } catch (\Exception $e){
            $error = $e->getMessage();
        } catch (\Error $e){
            $error = $e->getMessage();
        }

        return $this->render('add_task_form.php', ['error' => $error]);
    }

    public function delete()
    {
        $repo = new TaskRepository($this->dataMapper);

        $id = $this->getParam('id', false);

        $repo->delete($id);

        return $this->redirect('/');
    }
}
