<?php

namespace App\Model;

use App\Service\Db\Db;

class Task
{
    /**
     * @var \App\Service\Db\Db
     */
    private $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    public function getById($id)
    {
        return $this->db->exec(
            '
            SELECT `id`, `user`, `email`, `text`, `status`, `edit` FROM `task` WHERE id = ?',
            [$id]
        )->row;
    }

    public function getAll($filter)
    {
        $order = '';
        if ($filter['order'] !== null) {
            switch (strtolower($filter['order'])) {
                case 'user':
                    $order = ' ORDER BY `user`';
                    break;
                case 'email':
                    $order = ' ORDER BY `email`';
                    break;
                case 'status':
                    $order = ' ORDER BY `status`';
                    break;
                default:
                    break;
            }
        }

        if ($filter['sort'] !== null) {
            switch (strtolower($filter['sort'])) {
                case 'asc':
                    $order .= ' ASC';
                    break;
                case 'desc':
                    $order .= ' DESC';
                    break;
                default:
                    break;
            }
        }

        $filter['page'] = isset($filter['page']) ? (int)$filter['page'] : 1;
        $filter['limit'] = isset($filter['limit']) ? (int)$filter['limit'] : 3;

        $sql = 'SELECT `id`, `user`, `email`, `text`, `status`, `edit` FROM `task` ' . $order;

        $pagination = new \App\Service\Db\Paginator($this->db, $sql);
        $results    = $pagination->getData($filter['limit'], $filter['page']);
        $results->links = $pagination->createLinks(5, "page-item");

        return $results;
    }

    public function add(array $data)
    {
        return $this->db->insert('task', [
            'user' => $data['user'],
            'email' => $data['email'],
            'text' => $data['text'],
        ]);
    }

    public function save(array $data)
    {
        $task = $this->getById($data['id']);
        if (empty($task)) {
            return false;
        }
        $edit = ((int)$task['edit'] === 1 || $task['text'] !== $data['text']) ? 1 : 0;

        return $this->db->update('task', [
            'user' => $data['user'],
            'email' => $data['email'],
            'text' => $data['text'],
            'status' => (int)$data['status'],
            'edit' => $edit,
        ], ['id' => $data['id']]);
    }

    public function validate($task)
    {
        $error = [];
        if (empty($task['user'])) {
            $error['user'] = 'Введите имя пользователя';
        }
        if (empty($task['email'])) {
            $error['email'] = 'Введите email';
        }
        if (filter_var($task['email'], FILTER_VALIDATE_EMAIL) === false) {
            $error['email'] = 'Неправильный формат email';
        }
        if (empty($task['text'])) {
            $error['text'] = 'Введите текст задачи';
        }

        return $error;
    }
}
