<?php
namespace Concrete\Package\CarsManager\Controller\SinglePage\Dashboard\CarsManager;

defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Package\CarsManager\Src\Page\Controller\CarManagerDashboardPageController;

use Concrete\Package\CarsManager\Src\Entity\CarModel;

use \Symfony\Component\Validator\Constraints as Assert;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use \Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CarModels extends CarManagerDashboardPageController
{
    use \Mainio\C5\SymfonyForms\Controller\Extension\SymfonyFormsExtension;

    public function view($parentID = null)
    {
        $parent = $this->getManufacturers($parentID);
        $this->set('carModels', $this->getCarModelsByParents($parent));
    }

    public function add()
    {
        $form = $this->buildForm(new CarModel());

        if ($this->saveForm($form)) {
            $this->flash('success', t('Car model added successfully.'));
            $this->redirect("/dashboard/cars_manager/car_models");
        }

        $this->set('form', $form->createView());
    }

    public function edit($carModelID = null)
    {
        $carModel = $this->getCarModels($carModelID)[0];
        $form = $this->buildForm($carModel);

        if ($this->saveForm($form)) {
            $this->flash('success', t('Car model modified successfully.'));
            $this->redirect("/dashboard/cars_manager/car_models");
        }

        $this->set('entryID', $carModel->getCarModelID());
        $this->set('form', $form->createView());
    }

    public function delete($carModelID = null)
    {
        $carModel = $this->getCarModels($carModelID)[0];
        if($this->deleteEntity($carModel)) {
            $this->flash('success', t('Car model deleted successfully.'));
        } else {
            $this->flash('error', t('An error occurred: please make sure this car model has no cars associated with it.'));
        }
        $this->redirect("/dashboard/cars_manager/car_models");
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
            ->add('capacity', NumberType::class, array(
                'constraints' => array(
                    new Assert\NotBlank(array('message' => t('The capacity cannot be empty.'))),
                ),
            ))
            ->add('yearPublished', NumberType::class, array(
                'constraints' => array(
                    new Assert\NotBlank(array('message' => t('The year cannot be empty.'))),
                ),
            ))
            ->add('manufacturer', EntityType::class, array(
                'class' => 'Application\Src\Entity\Manufacturer',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'choice_label' => 'getName',
                'constraints' => array(
                    new Assert\NotBlank(array('message' => t('You must specify a manufacturer.'))),
                ),
            ))
            ->getForm();
        return $form;
    }

}
