<?php
namespace Concrete\Package\CarsManager\Src;

defined('C5_EXECUTE') or die(_("Access Denied."));

use Page;
use Permissions;
use Route;
use User;

class PackageRouteProvider
{
    public static function registerRoutes()
    {
        Route::register('/cars_manager/manufacturers', '\Concrete\Package\CarsManager\Controller\Backend\CarManager::manufacturers');
        Route::register('/cars_manager/manufacturers/{mID}', '\Concrete\Package\CarsManager\Controller\Backend\CarManager::manufacturers');

        Route::register('/cars_manager/manufacturers/{mID}/car_models', '\Concrete\Package\CarsManager\Controller\Backend\CarManager::carModels');
        Route::register('/cars_manager/manufacturers/{mID}/car_models/{cmID}', '\Concrete\Package\CarsManager\Controller\Backend\CarManager::carModels');

        Route::register('/cars_manager/manufacturers/{mID}/car_models/{cmID}/cars', '\Concrete\Package\CarsManager\Controller\Backend\CarManager::carsByCarModels');
        Route::register('/cars_manager/manufacturers/{mID}/car_models/{cmID}/cars/{cID}', '\Concrete\Package\CarsManager\Controller\Backend\CarManager::carsByCarModels');

        Route::register('/cars_manager/owners', '\Concrete\Package\CarsManager\Controller\Backend\CarManager::owners');
        Route::register('/cars_manager/owners/{oID}', '\Concrete\Package\CarsManager\Controller\Backend\CarManager::owners');

        Route::register('/cars_manager/owners/{oID}/cars', '\Concrete\Package\CarsManager\Controller\Backend\CarManager::carsByOwners');
        Route::register('/cars_manager/owners/{oID}/cars/{cID}', '\Concrete\Package\CarsManager\Controller\Backend\CarManager::carsByOwners');
    }
}
