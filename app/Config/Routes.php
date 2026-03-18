<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Summary::index');
$routes->get('/summary', 'Summary::index');
$routes->get('/ai', 'ChatAI::index');
$routes->get('/settings', 'Settings::index');




# Auth
$routes->get('/user/login', 'Auth::login');
$routes->post('/user/login', 'Auth::authenticate');
$routes->get('/user/logout', 'Auth::logout');


# API
$routes->post('/auth/register', 'Auth::reg');
$routes->post('/auth/process_login', 'Auth::processLogin');


#settings
$routes->post('/settings/updatePersonalData', 'CrudController::updatePersonalData');
$routes->post('/settings/saveWardrobe', 'CrudController::saveWardrobe');
