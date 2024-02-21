<?php

declare(strict_types=1);

namespace Maurerd\Webentwicklung\View;
use Maurerd\Webentwicklung\View\AbstractView;
use Laminas\Diactoros\Response;



class HomePage extends AbstractView
{
    /**
     * @return string
     */
    protected function getTemplatePath(): string
    {
        return '/view/homepage.html';
    }


    /**
     * @param array $arguments
     * @return string
     */
    public function render(array $arguments = []): string
    {
        return parent::render($arguments);
    }
}