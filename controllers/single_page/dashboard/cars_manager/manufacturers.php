<?php
namespace Concrete\Package\CarsManager\Controller\SinglePage\Dashboard\CarsManager;

defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Package\CarsManager\Src\Page\Controller\CarManagerDashboardPageController;

use Concrete\Package\CarsManager\Src\Entity\Manufacturer;

use \Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class Manufacturers extends CarManagerDashboardPageController
{
    use \Mainio\C5\SymfonyForms\Controller\Extension\SymfonyFormsExtension;

    public function view()
    {
        $this->set('manufacturers', $this->getManufacturers());
    }

    public function add()
    {
        $form = $this->buildForm(new Manufacturer());

        if ($this->saveForm($form)) {
            $this->flash('success', t('Manufacturer added successfully.'));
            $this->redirect("/dashboard/cars_manager/manufacturers");
        }

        $this->set('form', $form->createView());
    }

    public function edit($manufacturerID = null)
    {
        $manufacturer = $this->getManufacturers($manufacturerID)[0];
        $form = $this->buildForm($manufacturer);

        if ($this->saveForm($form)) {
            $this->flash('success', t('Manufacturer modified successfully.'));
            $this->redirect("/dashboard/cars_manager/manufacturers");
        }

        $this->set('entryID', $manufacturer->getManufacturerID());
        $this->set('form', $form->createView());
    }

    public function delete($manufacturerID = null)
    {
        $manufacturer = $this->getManufacturers($manufacturerID)[0];
        if($this->deleteEntity($manufacturer)) {
            $this->flash('success', t('Manufacturer deleted successfully.'));
        } else {
            $this->flash('error', t('An error occurred: please make sure this manufacturer has no car models associated with it.'));
        }
        $this->redirect("/dashboard/cars_manager/manufacturers");
    }

    protected function buildForm($entity, $options = array())
    {
        $formFactory = $this->getFormFactory();

        $form = $formFactory->createBuilder('\Symfony\Component\Form\Extension\Core\Type\FormType', $entity, $options)
            ->add('name', TextType::class, array(
                'constraints' => array(
                    new Assert\NotBlank(array('message' => t('The name cannot be empty.'))),
                ),
            ))
            ->add('homeCountry', CountryType::class, array(
                'constraints' => array(
                    new Assert\NotBlank(array('message' => t('The home country cannot be empty.'))),
                ),
            ))
            ->getForm();

        return $form;
    }

}
