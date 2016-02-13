<?php
namespace Concrete\Package\CarsManager\Src\Entity;

defined('C5_EXECUTE') or die(_("Access Denied."));

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="CarsManagerCar")
 */
class Car implements \JsonSerializable
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    protected $carID;

    /**
     * @Column(type="string", length=255)
     */
    protected $registrationNumber;

    /**
     * @Column(type="integer", nullable=false)
     */
    protected $manufacturingYear;

    /**
     * @ManyToOne(targetEntity="Concrete\Package\CarsManager\Src\Entity\CarModel")
     * @JoinColumn(name="carModelID", referencedColumnName="carModelID")
     **/
    protected $carModel;

    /**
     * @ManyToOne(targetEntity="Concrete\Package\CarsManager\Src\Entity\Owner")
     * @JoinColumn(name="ownerID", referencedColumnName="ownerID")
     **/
    protected $owner;

    public function getCarID()
    {
        return $this->carID;
    }

    public function getRegistrationNumber()
    {
        return $this->registrationNumber;
    }

    public function setRegistrationNumber($registrationNumber)
    {
        $this->registrationNumber = $registrationNumber;

        return $this;
    }

    public function getManufacturingYear()
    {
        return $this->manufacturingYear;
    }

    public function setManufacturingYear($manufacturingYear)
    {
        $this->manufacturingYear = $manufacturingYear;

        return $this;
    }

    public function getCarModel()
    {
         return $this->carModel;
    }

    public function setCarModel($carModel)
    {
        $this->carModel = $carModel;

        return $this;
    }

    public function getOwner()
    {
         return $this->owner;
    }

    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    public function jsonSerialize() {
        return [
            'id' => $this->carID,
            'registrationNumber' => $this->registrationNumber,
            'manufacturingYear' => $this->manufacturingYear,
            'carModel' => $this->carModel->getCarModelID(),
            'owner' => $this->owner->getOwnerID()
        ];
    }

}
