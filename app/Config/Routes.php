<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// Home Routes
$routes->get('/', 'Home::index');
$routes->match(['get', 'post'], 'home', 'Home::index');
$routes->match(['get', 'post'], 'Home', 'Home::index');
$routes->match(['get', 'post'], 'home/index', 'Home::index');
$routes->match(['get', 'post'], 'Home/index', 'Home::index');
$routes->post('home/update', 'Home::update');
$routes->post('Home/update', 'Home::update');
$routes->post('home/updateSaldo', 'Home::updateSaldo');
$routes->post('Home/updateSaldo', 'Home::updateSaldo');

// Auth Routes
$routes->get('auth', 'Auth::index');
$routes->get('Auth', 'Auth::index');
$routes->get('auth/index', 'Auth::index');
$routes->get('Auth/index', 'Auth::index');
$routes->post('auth/loginProcess', 'Auth::loginProcess');
$routes->post('Auth/loginProcess', 'Auth::loginProcess');
$routes->get('auth/logout', 'Auth::logout');
$routes->get('Auth/logout', 'Auth::logout');

// KbCalculator Routes
$routes->get('kbCalculator', 'KbCalculator::index');
$routes->get('KbCalculator', 'KbCalculator::index');
$routes->get('kbCalculator/index', 'KbCalculator::index');
$routes->get('KbCalculator/index', 'KbCalculator::index');
$routes->post('kbCalculator/storeStart', 'KbCalculator::storeStart');
$routes->post('KbCalculator/storeStart', 'KbCalculator::storeStart');
$routes->post('kbCalculator/storeEnd', 'KbCalculator::storeEnd');
$routes->post('KbCalculator/storeEnd', 'KbCalculator::storeEnd');
$routes->post('kbCalculator/update', 'KbCalculator::update');
$routes->post('KbCalculator/update', 'KbCalculator::update');

// Series Tracker Routes
$routes->get('series/list', 'SeriesController::list');
$routes->post('series/store', 'SeriesController::store');
$routes->put('series/update/(:num)', 'SeriesController::update/$1');
$routes->post('series/episodes/batch-update', 'SeriesController::batchUpdateEpisodes');
$routes->delete('series/delete/(:num)', 'SeriesController::delete/$1');
$routes->post('series/episode/toggle', 'SeriesController::toggleEpisode');
$routes->get('series/list-disabled', 'SeriesController::listDisabled');
$routes->post('series/disable/(:num)', 'SeriesController::disable/$1');
$routes->post('series/restore/(:num)', 'SeriesController::restore/$1');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
