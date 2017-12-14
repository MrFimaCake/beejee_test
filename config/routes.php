<?php

/** @var \app\Router $router */

$router->get('/', 'TaskController::index', ['page' => 1]);
$router->get('/tasks', 'TaskController::index', ['page' => 1]);
$router->get('/task/create', 'TaskController::create');
$router->post('/task/create-save', 'TaskController::createSave');
$router->get('/task/update', 'TaskController::update')->adminOnly(true);
$router->post('/task/update-save', 'TaskController::updateSave')->adminOnly(true);
$router->get('/task/delete', 'TaskController::delete')->adminOnly(true);
$router->get('/admin', 'AdminController::form');
$router->post('/admin', 'AdminController::login');
$router->post('/admin/logout', 'AdminController::logout');