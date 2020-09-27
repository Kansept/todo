<?php

use App\Controller\AdminController;
use App\Controller\TaskController;

return [
    '/' => TaskController::class,
    'task' => TaskController::class,
    'admin' => AdminController::class, 
];