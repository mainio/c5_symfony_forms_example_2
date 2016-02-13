<?php
namespace Concrete\Package\CarsManager\Src\Entity;

defined('C5_EXECUTE') or die(_("Access Denied."));

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="CarsManagerCarModel")
 */
class CarModel implements \JsonSerializable
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    protected $carModelID;

    /**
     * @Column(type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @Column(type="integer", nullable=false)
     */
    protected $capacity;

    /**
     * @Column(type="integer", nullable=false)
     */
    protected $yearPublished;

    /**
     * @ManyToOne(targetEntity="Concrete\Package\CarsManager\Src\Entity\Manufacturer")
     * @JoinColumn(name="manufacturerID", referencedColumnName="manufacturerID")
     **/
    protected $manufacturer;

    /**
     * @OneToMany(targetEntity="Concrete\Package\CarsManager\Src\Entity\Car", mappedBy="carModel")
     **/
    protected $cars;

    public function getCarModelID()
    {
         return $this->carModelID;
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

    public function getCapacity()
    {
         return $this->capacity;
    }

    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getYearPublished()
    {
         return $this->yearPublished;
    }

    public function setYearPublished($yearPublished)
    {
        $this->yearPublished = $yearPublished;

        return $this;
    }

    public function getManufacturer() {
        return $this->manufacturer;
    }

    public function setManufacturer($manufacturer) {
        $this->manufacturer = $manufacturer;

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
            'id' => $this->carModelID,
            'name' => $this->name,
            'capacity' => $this->capacity,
            'yearPublished' => $this->yearPublished,
            'manufacturer' => $this->manufacturer->getManufacturerID(),
            'cars' => $carsID
        ];
    }

}
