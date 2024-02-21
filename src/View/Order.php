<?php

declare(strict_types=1);

namespace Maurerd\Webentwicklung\View;

class Order extends AbstractView
{
    protected function getTemplatePath(): string
    {
       return '/view/homepage.html';
    }
}
