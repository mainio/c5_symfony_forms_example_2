<?php
namespace Concrete\Package\CarsManager\Src\Entity;

defined('C5_EXECUTE') or die(_("Access Denied."));

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="CarsManagerOwner")
 */
class Owner implements \JsonSerializable
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    protected $ownerID;

    /**
     * @Column(type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @OneToMany(targetEntity="Concrete\Package\CarsManager\Src\Entity\Car", mappedBy="owner")
     **/
    protected $cars;

    public function getOwnerID()
    {
        return $this->ownerID;
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

    public function getCars()
    {
         return $this->cars;
    }

    public function setCars($cars)
    {
        $this->cars = $cars;

        return $this;
    }

    public function jsonSerialize() {
        $cars = $this->cars;
        $carsID = array();
        if(is_object($cars) && $cars > 0) {
            foreach ($cars as $car) {
                array_push($carsID, $car->getCarID());
            }
        }

        return [
            'id' => $this->ownerID,
            'name' => $this->name,
            'cars' => $carsID
        ];
    }

}
