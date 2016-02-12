<?php
namespace Concrete\Package\CarsManager\Src\Page\Controller;

use Request;

use Mainio\C5\Twig\Page\Controller\DashboardPageController;

class CarManagerDashboardPageController extends DashboardPageController
{
    use \Concrete\Package\CarsManager\Src\Manager\CarManager;

    protected function saveForm($form)
    {
       $request = Request::getInstance();
       $form->handleRequest($request);

       if ($form->isValid()) {
           $object = $form->getData();
           $entity = $form->getData();
           return $this->saveEntity($entity);
       } else {
           // The boolean 'true' is for getting all the errors on the form.
           $errors = $form->getErrors(true);
           foreach ($errors as $err) {
               $this->error->add($err->getMessage());
           }
       }
       return false;
   }

}
