<?php

namespace Concrete\Package\CarsManager\Controller\SinglePage\Dashboard;
use \Concrete\Core\Page\Controller\DashboardPageController;

class CarsManager extends DashboardPageController
{

    public function view()
    {
        $this->redirect('dashboard/cars_manager/owners');
    }

}
