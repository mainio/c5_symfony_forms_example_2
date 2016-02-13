<?php
namespace Concrete\Package\CarsManager\Src\Entity;

defined('C5_EXECUTE') or die(_("Access Denied."));

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="CarsManagerManufacturer")
 */
class Manufacturer implements \JsonSerializable
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    protected $manufacturerID;

    /**
     * @Column(type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @Column(type="string", length=255, nullable=false)
     */
    protected $homeCountry;

    /**
     * @OneToMany(targetEntity="Concrete\Package\CarsManager\Src\Entity\CarModel", mappedBy="manufacturer")
     **/
    protected $carModels;

    public function getManufacturerID()
    {
        return $this->manufacturerID;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getHomeCountry()
    {
        return $this->homeCountry;
    }

    public function setHomeCountry($homeCountry)
    {
        $this->homeCountry = $homeCountry;

        return $this;
    }

    public function getCarModels()
    {
         return $this->carModels;
    }

    public function setCarModels($carModels)
    {
        $this->carModels = $carModels;

        return $this;
    }

    public function jsonSerialize() {
        $carModels = $this->carModels;
        $carModelsID = array();
        if(is_object($carModels) && $carModels > 0) {
            foreach ($carModels as $carModel) {
                array_push($carModelsID, $carModel->getCarModelID());
            }
        }

        return [
            'id' => $this->manufacturerID,
            'name' => $this->name,
            'homeCountry' => $this->homeCountry,
            'carModels' => $carModelsID
        ];
    }

}
