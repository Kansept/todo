<?php if ($isNew) : ?>
    <h1>Создать задачу</h1>
<?php else : ?>
    <h1>Редактировать задачу</h1>
<?php endif ?>

<form action="" method="POST">
    <div class="form-group">
        <label for="user">Имя пользователя</label>
        <input type="text" class="form-control <?= (!empty($error['user']) ? 'is-invalid' : '') ?>" name="user" id="user" value="<?= htmlspecialchars($task['user']) ?>" required>
        <div class="invalid-feedback"><?= $error['user'] ?? '' ?></div>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control <?= (!empty($error['email']) ? 'is-invalid' : '') ?>" name="email" id="email" value="<?= htmlspecialchars($task['email']) ?>" required>
        <div class="invalid-feedback"><?= $error['email'] ?? '' ?></div>
    </div>
    <div class="form-group">
        <label for="email">Текст</label>
        <textarea class="form-control <?= (!empty($error['text']) ? 'is-invalid' : '') ?>" name="text" id="text" required><?= htmlspecialchars($task['text']) ?></textarea>
        <div class="invalid-feedback"><?= $error['text'] ?? '' ?></div>
    </div>
    <?php if (!$isNew) : ?>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" name="status" id="status" <?= ((int)$task['status'] ? 'checked' : '') ?> value="1">
            <label class="form-check-label" for="status">Выполнено</label>
        </div>
        <input type="hidden" name="id" value="<?= $task['id'] ?>">
    <?php endif ?>
    <button type="submit" class="btn btn-primary">Сохранить</button>
</form>