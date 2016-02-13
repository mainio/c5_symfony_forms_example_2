<?php
namespace Concrete\Package\CarsManager\Controller\SinglePage\Dashboard\CarsManager;

defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Package\CarsManager\Src\Page\Controller\CarManagerDashboardPageController;

use Concrete\Package\CarsManager\Src\Entity\Car;

use \Symfony\Component\Validator\Constraints as Assert;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use \Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class Cars extends CarManagerDashboardPageController
{
    use \Mainio\C5\SymfonyForms\Controller\Extension\SymfonyFormsExtension;

    public function view($parentID = null, $category = null)
    {
        if($category == 'car_models') {
            $parent = $this->getCarModels($parentID);
        } else if($category == 'owners') {
            $parent = $this->getOwners($parentID);
        } else {
            $parent = $this->getOwners($parentID);
        }
        $this->set('cars', $this->getCarsByParents($parent));
    }

    public function add()
    {
        $form = $this->buildForm(new Car());

        if ($this->saveForm($form)) {
            $this->flash('success', t('Car added successfully.'));
            $this->redirect("/dashboard/cars_manager/cars");
        }

        $this->set('form', $form->createView());
    }

    public function edit($carID = null)
    {
        $car = $this->getCars($carID)[0];
        $form = $this->buildForm($car);

        if ($this->saveForm($form)) {
            $this->flash('success', t('Car modified successfully.'));
            $this->redirect("/dashboard/cars_manager/cars");
        }

        $this->set('entryID', $car->getCarID());
        $this->set('form', $form->createView());
    }

    public function delete($carID = null)
    {
        $car = $this->getCars($carID)[0];
        if($this->deleteEntity($car)) {
            $this->flash('success', t('Car deleted successfully.'));
        } else {
            $this->flash('error', t('An error occurred.'));
        }
        $this->redirect("/dashboard/cars_manager/cars");
    }

    protected function buildForm($entity, $options = array())
    {
        $formFactory = $this->getFormFactory();

        $form = $formFactory->createBuilder('Symfony\Component\Form\Extension\Core\Type\FormType', $entity, $options)
            ->add('registrationNumber', TextType::class, array(
                'constraints' => array(
                    new Assert\NotBlank(array('message' => t('The registration number cannot be empty.'))),
                ),
            ))
            ->add('manufacturingYear', NumberType::class, array(
                'constraints' => array(
                    new Assert\NotBlank(array('message' => t('The manufacturing year cannot be empty.'))),
                ),
            ))
            ->add('carModel', EntityType::class, array(
                'class' => 'Concrete\Package\CarsManager\Src\Entity\CarModel',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'choice_label' => 'getName',
                'constraints' => array(
                    new Assert\NotBlank(array('message' => t('You must specify a car model.'))),
                ),
            ))
            ->add('owner', EntityType::class, array(
                'class' => 'Concrete\Package\CarsManager\Src\Entity\Owner',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'choice_label' => 'getName',
                'constraints' => array(
                    new Assert\NotBlank(array('message' => t('You must specify an owner.'))),
                ),
            ))
            ->getForm();
        return $form;
    }

}
