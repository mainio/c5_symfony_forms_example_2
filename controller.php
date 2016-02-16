<?php
namespace Concrete\Package\CarsManager;

defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Package\CarsManager\Src\PackageRouteProvider;
use Core;
use Mainio\C5\Twig\TwigServiceProvider;
use Mainio\C5\Twig\Page\Single as SinglePage;
use Package;
use Concrete\Core\Foundation\ClassLoader;

class Controller extends Package
{
    protected $pkgHandle = 'cars_manager';
    protected $appVersionRequired = '5.7.4';
    protected $pkgVersion = '0.0.1';

    public function getPackageName()
    {
        return t("Car Management System Symfony Forms Example");
    }

    public function getPackageDescription()
    {
        return t("A more elaborate example of using Symfony forms and validators within the concrete5 package context.");
    }

    public function getPackageEntitiesPath()
    {
        return $this->getPackagePath() . '/' . DIRNAME_CLASSES . '/Entity';
    }

    public function on_start()
    {
        $this->loadDependencies();
        PackageRouteProvider::registerRoutes();
        // Register the twig services for the single pages
        $this->registerTwigServices($this);
    }

    public function install()
    {
        if (version_compare(phpversion(), '5.4', '<')) {
            throw new \Exception(t("Minimum PHP version required by this package is 5.4. Please update your PHP."));
        }
        $fs = new \Illuminate\Filesystem\Filesystem();
        if (!$fs->exists(dirname(__FILE__) . '/vendor/autoload.php')) {
            throw new \Exception(t("Composer packages have not been installed for this add-on. Please follow the installation instructions!"));
        }

        // We need to register the autoloaders for the DB uninstallation to
        // work properly. This would not otherwise be done in the install
        // function.
        ClassLoader::getInstance()->registerPackage($this->pkgHandle);

        $pkg = parent::install();
        $this->loadDependencies();
        $this->clearTwigCache($pkg);
        $this->installSinglePages($pkg);
    }

    public function upgrade()
    {
        parent::upgrade();
        $this->clearTwigCache($this);
    }

    protected function installSinglePages(Package $pkg)
    {
        $sp = SinglePage::add('/dashboard/cars_manager', $pkg);
        $sp = SinglePage::add('/dashboard/cars_manager/manufacturers', $pkg);
        $sp = SinglePage::add('/dashboard/cars_manager/car_models', $pkg);
		$sp = SinglePage::add('/dashboard/cars_manager/cars', $pkg);
        $sp = SinglePage::add('/dashboard/cars_manager/owners', $pkg);
    }

    protected function clearTwigCache(Package $pkg)
    {
        $this->registerTwigServices($pkg);
        Core::make('cars_manager/twig')->clearCacheDirectory();
    }

    protected function registerTwigServices(Package $pkg)
    {
        $spt = new TwigServiceProvider(Core::getFacadeApplication(), $pkg);
        $spt->register();
    }

    protected function loadDependencies()
    {
        // No other way of managing the composer dependencies currently.
        // See: https://github.com/concrete5/concrete5-5.7.0/issues/360
        $filesystem = new \Illuminate\Filesystem\Filesystem();
        $loader = $filesystem->getRequire(dirname(__FILE__) . '/vendor/autoload.php');
        $this->intlFix($loader);
    }

    protected function intlFix(\Composer\Autoload\ClassLoader $loader)
    {
        // When defining the load path for the 'Collator' class, it messes up
        // punic as punic expects PHP's intl to be installed when this class
        // exists in the global namespace. The symfony-intl's Collator only
        // works with the 'en' locale which becomes a problem e.g. with the
        // c5's default locale (en_US). The 'Collator' class isn't used
        // anywhere in this add-on, so it is not needed.
        // $loader->addClassMap(array('Collator' => null));
    }
}
