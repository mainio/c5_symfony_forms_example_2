<?php
namespace Concrete\Package\CarsManager\Controller\SinglePage\Dashboard\CarsManager;

defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Package\CarsManager\Src\Page\Controller\CarManagerDashboardPageController;

use Concrete\Package\CarsManager\Src\Entity\Owner;

use \Symfony\Component\Validator\Constraints as Assert;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use \Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class Owners extends CarManagerDashboardPageController
{
    use \Mainio\C5\SymfonyForms\Controller\Extension\SymfonyFormsExtension;

    public function view()
    {
        $this->set('owners', $this->getOwners());
    }

    public function add()
    {
        $form = $this->buildForm(new Owner());

        if ($this->saveForm($form)) {
            $this->flash('success', t('Owner added successfully.'));
            $this->redirect("/dashboard/cars_manager/owners");
        }

        $this->set('form', $form->createView());
    }

    public function edit($ownerID = null)
    {
        $owner = $this->getOwners($ownerID)[0];
        $form = $this->buildForm($owner);

        if ($this->saveForm($form)) {
            $this->flash('success', t('Owner modified successfully.'));
            $this->redirect("/dashboard/cars_manager/owners");
        }

        $this->set('entryID', $owner->getOwnerID());
        $this->set('form', $form->createView());
    }

    public function delete($ownerID = null)
    {
        $owner = $this->getOwners($ownerID)[0];
        if($this->deleteEntity($owner)) {
            $this->flash('success', t('Owner deleted successfully.'));
        } else {
            $this->flash('error', t('An error occurred: please make sure this owner has no cars associated with it.'));
        }
        $this->redirect("/dashboard/cars_manager/owners");
    }

    protected function buildForm($entity, $options = array())
    {
        $formFactory = $this->getFormFactory();

        $form = $formFactory->createBuilder('Symfony\Component\Form\Extension\Core\Type\FormType', $entity, $options)
            ->add('name', TextType::class, array(
                'constraints' => array(
                    new Assert\NotBlank(array('message' => t('The name cannot be empty.'))),
                ),
            ))
            ->getForm();
        return $form;
    }

}
