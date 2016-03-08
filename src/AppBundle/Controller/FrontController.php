<?php

namespace AppBundle\Controller;

use AppBundle\Service\TemplateAware;

class FrontController
{
    use TemplateAware;

    /**
     * Homepage
     */
    public function homeAction()
    {
        return $this->render('AppBundle:Front:home.html.twig', []);
    }

}
