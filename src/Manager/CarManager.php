<?php
namespace Concrete\Package\CarsManager\Src\Manager;

use ORM;

use Concrete\Package\CarsManager\Src\Entity\Manufacturer;
use Concrete\Package\CarsManager\Src\Entity\CarModel;
use Concrete\Package\CarsManager\Src\Entity\Car;
use Concrete\Package\CarsManager\Src\Entity\Owner;

trait CarManager
{

    protected function getManufacturers($id = null)
    {
        $rep = $this->getManufacturerRepository();
        if(!empty($id) && $id !== '*') {
            return is_null($rep->find($id)) ? array() : array($rep->find($id));
        }
        return $rep->findAll();
    }

    protected function getCarModels($id = null)
    {
        $rep = $this->getCarModelRepository();
        if(!empty($id) && $id !== '*') {
            return is_null($rep->find($id)) ? array() : array($rep->find($id));
        }
        return $rep->findAll();
    }

    protected function getCars($id = null)
    {
        $rep = $this->getCarRepository();
        if(!empty($id) && $id !== '*') {
            return is_null($rep->find($id)) ? array() : array($rep->find($id));
        }
        return $rep->findAll();
    }

    protected function getOwners($id = null)
    {
        $rep = $this->getOwnerRepository();
        if(!empty($id) && $id !== '*') {
            return is_null($rep->find($id)) ? array() : array($rep->find($id));
        }
        return $rep->findAll();
    }

    protected function getCarModelsByParents($parents = null)
    {
        $rep = $this->getCarModelRepository();
        $carModels = array();
        if(!empty($parents)) {
            foreach ($parents as $parent) {
                $tempModels = $rep->findByManufacturer($parent);
                if(!empty($tempModels)) {
                    $carModels = array_merge($carModels, $tempModels);
                }
            }
        }
        return $carModels;
    }

    protected function getCarsByParents($parents = null)
    {
        $rep = $this->getCarRepository();
        $cars = array();
        if(!empty($parents)) {
            foreach ($parents as $parent) {
                if($parent instanceof Owner) {
                    $tempCars = $rep->findByOwner($parent);
                    if(!empty($tempCars)) {
                        $cars = array_merge($cars, $tempCars);
                    }
                } else if($parent instanceof CarModel) {
                    $tempCars = $rep->findByCarModel($parent);
                    if(!empty($tempCars)) {
                        $cars = array_merge($cars, $tempCars);
                    }
                }
            }
        }
        return $cars;
    }

    protected function saveEntity($entity)
    {
        try {
            $entityManager = $this->em();
            $entityManager->persist($entity);
            $entityManager->flush();
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    protected function deleteEntity($entity)
    {
        if((method_exists($entity, 'getCars') && count($entity->getCars()) > 0) ||      (method_exists($entity, 'getCarModels') && count($entity->getCarModels()) > 0)) {
            return false;
        }
        try {
            $entityManager = $this->em();
            $entityManager->remove($entity);
            $entityManager->flush();
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    protected function getManufacturerRepository()
    {
        return $this->em()->getRepository('Concrete\Package\CarsManager\Src\Entity\Manufacturer');
    }

    protected function getCarModelRepository()
    {
        return $this->em()->getRepository('Concrete\Package\CarsManager\Src\Entity\CarModel');
    }

    protected function getCarRepository()
    {
        return $this->em()->getRepository('Concrete\Package\CarsManager\Src\Entity\Car');
    }

    protected function getOwnerRepository()
    {
        return $this->em()->getRepository('Concrete\Package\CarsManager\Src\Entity\Owner');
    }

    protected function em()
    {
        return ORM::entityManager();
    }

}
