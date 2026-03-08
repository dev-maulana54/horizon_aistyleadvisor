<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Summary::index');
$routes->get('/summary', 'Summary::index');
$routes->get('/ai', 'ChatAI::index');
$routes->get('/settings', 'Settings::index');
