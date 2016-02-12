<?php
namespace Concrete\Package\CarsManager\Controller\Backend;

use ORM;
use Loader;
use Controller;
use Concrete\Package\CarsManager\Src\Entity\Manufacturer;

class CarManager extends Controller
{
    use \Concrete\Package\CarsManager\Src\Manager\CarManager;

	public function manufacturers($mID = null)
    {
        print json_encode($this->getManufacturers($mID));
	}

    public function carModels($mID = null, $cmID = null)
    {
        $parent = $this->getManufacturers($mID);
        $carModelsByParent = $this->getCarModelsByParents($parent);
        $carModelsByID = $this->getCarModels($cmID);

        $car_models = $this->intersectArrays($carModelsByParent, $carModelsByID);
        print json_encode($car_models);
	}

    public function carsByCarModels($mID = null, $cmID = null, $cID = null)
    {
        $parent = $this->getManufacturers($mID);
        $carModelsByParent = $this->getCarModelsByParents($parent);
        $carModelsByID = $this->getCarModels($cmID);

        $car_models = $this->intersectArrays($carModelsByParent, $carModelsByID);

        $carsByParent = $this->getCarsByParents($car_models);
        $carsByID = $this->getCars($cID);

        $cars = $this->intersectArrays($carsByParent, $carsByID);
        print json_encode($cars);
	}

    public function carsByOwners($oID = null, $cID = null)
    {
        $parent = $this->getOwners($oID);
        $carsByParent = $this->getCarsByParents($parent);
        $carsByID = $this->getCars($cID);

        $cars = $this->intersectArrays($carsByParent, $carsByID);
        print json_encode($cars);
	}

    public function owners($oID = null)
    {
        print json_encode($this->getOwners($oID));
	}

    private function intersectArrays($entities1, $entities2) {
        $intersection = array();
        foreach ($entities1 as $e1) {
            foreach ($entities2 as $e2) {
                if($e1 === $e2) array_push($intersection, $e1);
            }
        }
        return $intersection;
    }

}
