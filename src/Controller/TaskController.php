<?php

namespace App\Controller;

use App\Model\Task;

class TaskController extends AbstractController
{
    /**
     * @var \App\Model\Task
     */
    private $task;

    public function init()
    {
        $this->task = new Task($this->db);
    }

    public function actionIndex()
    {
        return $this->render('/task/index.html.php', [
            'tasks' => $this->task->getAll([
                'page'  => $_GET['page'] ?? 1,
                'limit' => $_GET['limit'] ?? 3,
                'order' => $_GET['order'] ?? null,
                'sort'  => $_GET['sort'] ?? null,
            ])
        ]);
    }

    public function actionNew()
    {
        $error = [];
        $task = [
            'user' => '',
            'email' => '',
            'text' => '',
        ];

        if ($this->isPost()) {
            $task = [
                'user' => $_POST['user'] ?? '',
                'email' => $_POST['email'] ?? '',
                'text' => $_POST['text'] ?? '',
            ];

            $error = $this->task->validate($task);

            if (empty($error)) {
                $this->task->add($task);
                $this->addFlash('success', 'Задачa успешно добалена');
                return $this->redirect('/task');
            }
        }

        return $this->render('/task/form.html.php', ['task' => $task, 'isNew' => true, 'error' => $error]);
    }

    public function actionEdit()
    {
        if ($this->user->isGuest()) {
            return $this->redirect('/admin');
        }

        $error = [];
        if ($this->isPost()) {
            $task = [
                'id' => $_POST['id'] ?? '',
                'user' => $_POST['user'] ?? '',
                'email' => $_POST['email'] ?? '',
                'text' => $_POST['text'] ?? '',
                'status' => $_POST['status'] ?? 0,
            ];

            $error = $this->task->validate($task);
            if (empty($error)) {
                $this->task->save($task);
                return $this->redirect('/');
            }
        }

        $id = (int)$_GET['id'] ?? 0;

        $task = $this->task->getById($id);
        if (empty($task)) {
            return $this->redirect('/');
        }

        return $this->render('/task/form.html.php', ['task' => $task, 'isNew' => false, 'error' => $error]);
    }
}
