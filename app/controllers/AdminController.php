<?php
/**
 * Created by PhpStorm.
 * User: mrcake
 * Date: 12/13/17
 * Time: 11:37 PM
 */

namespace app\controllers;


class AdminController extends Controller
{
    public function form()
    {
        return $this->render('admin_form.php');
    }

    public function login()
    {
        $params = $this->request->getRequestParams();

        $username = $params['username'] ?? '';
        $password = $params['password'] ?? '';

        if ($username == 'admin' && $password == '123') {
            $this->applyUser($username);
        }

        return $this->redirect('/');
    }


    public function logout()
    {
        $this->logoutUser();
        return $this->redirect('/');
    }

    private function logoutUser()
    {
        $_SESSION['username'] = null;
    }

    private function applyUser($username)
    {
        $_SESSION['username'] = $username;
        $this->request->initUser();
    }
}