<?php

namespace App\Controller;

use App\Model\Task;

class AdminController extends AbstractController
{
    public function init()
    {
        $this->task = new Task($this->db);
    }

    public function actionIndex()
    {
        if (!$this->user->isGuest()) {
            return $this->redirect('/');
        }

        if ($this->isPost()) {
            $login = $_POST['login'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($login === 'admin' && $password === '123') {
                $this->user->login($login);
                return $this->redirect('/');
            } else {
                $this->addFlash('danger', 'Ошибка авторизации');
                return $this->redirect('/admin');
            }
        }
        return $this->render('/admin/login.html.php');
    }

    public function actionLogout()
    {
        if ($this->isPost()) {
            $this->user->logout();
        }
        return $this->redirect('/');
    }
}
