<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Summary::index');
$routes->get('/summary', 'Summary::index');
$routes->get('/settings', 'Settings::index');


#Chat AI
$routes->get('/ai', 'ChatAI::index');
$routes->get('/ai/(:segment)', 'ChatAI::loadChat/$1');
$routes->post('ai/upload-image',         'ChatAI::uploadImage');
$routes->post('ai/save-generated-image', 'ChatAI::saveGeneratedImage'); // BARU: proxy download+blur
$routes->post('/ai/send', 'ChatAI::sendMessage');
$routes->get('ai/proxy-image', 'ChatAI::proxyImage'); // BARU: proxy wardrobe image untuk gpt-image-1
# Auth
$routes->get('/user/login', 'Auth::login');
$routes->post('/user/login', 'Auth::authenticate');
$routes->get('/user/logout', 'Auth::logout');


# API
$routes->post('/auth/register', 'Auth::reg');
$routes->post('/auth/process_login', 'Auth::processLogin');
$routes->get('getWardrobeUser/(:num)', 'CrudController::getWardrobeUser/$1');

#FEATURE
$routes->get('/ai_tryon', 'Ai_tryon::index');

#settings
$routes->post('/settings/updatePersonalData', 'CrudController::updatePersonalData');
$routes->post('/settings/saveWardrobe', 'CrudController::saveWardrobe');
