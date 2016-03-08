<?php

namespace AppBundle\Service;

use Symfony\Component\Templating\EngineInterface;

trait TemplateAware {

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @param EngineInterface $templating
     */
    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    /**
     * @param $view
     * @param array $parameters
     * @param Response $response
     * @return mixed
     */
    public function render($view, $parameters = array(), Response $response = null)
    {
        return $this->templating->renderResponse($view, $parameters, $response);
    }
}