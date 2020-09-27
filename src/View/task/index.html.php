<?php
/**
 * @var App\Model\User $user
 */
?>

<div class="row my-4">
    <div class="col-md-12 text-right">
        <a href="/task/new" class="btn btn-primary">Добавить задачу</a>
    </div>
</div>

<div class="row my-4">
    <div class="col-md-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">
                        имя пользователя 
                        <a href="/task/index?order=user&sort=asc">&uarr;</a> <a href="/task/index?order=user&sort=desc">&darr;</a>
                    </th>
                    <th scope="col">
                        email
                        <a href="/task/index?order=email&sort=asc">&uarr;</a> <a href="/task/index?order=email&sort=desc">&darr;</a>
                    </th>
                    <th scope="col">
                        текст задачи
                    </th>
                    <th scope="col">
                        статус
                        <a href="/task/index?order=status&sort=asc">&uarr;</a> <a href="/task/index?order=status&sort=desc">&darr;</a>
                    </th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tasks->data)) : ?>
                    <tr>
                        <td colspan="6" class="text-center">Список задач пуст</td>
                    </tr>
                <?php else : ?>
                    <?php $i = 1; ?>
                    <?php foreach ($tasks->data as $task) : ?>
                        <tr>
                            <th scope="row"><?= $i++ ?></th>
                            <td><?= htmlentities($task['user']) ?></td>
                            <td><?= htmlentities($task['email']) ?></td>
                            <td><?= htmlentities($task['text']) ?></td>
                            <td>
                                <?php if ((int)$task['status'] === 1) : ?>
                                    <span class="text-success">выполнено</span>
                                <?php else : ?>
                                    <span>не выполнено</span>
                                <?php endif ?>

                                <?php if ((int)$task['edit'] === 1) : ?>
                                    <span class="d-block text-muted">отредактировано администратором</span>
                                <?php endif ?>
                            </td>
                            <td>
                                <?php if (!$user->isGuest()) : ?>
                                    <a href="/task/edit?id=<?= $task['id'] ?>">Редактировать</a>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-12 text-center">
        <?= $tasks->links ?>
    </div>
</div>