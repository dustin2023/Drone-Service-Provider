<?php

declare(strict_types=1);

namespace Maurerd\Webentwicklung\View\Auth;

use Maurerd\Webentwicklung\View\AbstractView;

class Register extends AbstractView
{
    /**
     * @return string
     */
    protected function getTemplatePath(): string
    {
        return '/view/auth/register.html';
    }

    /**
     * @param array $arguments
     * @return string
     */
    public function render(array $arguments = []): string
    {
        $arguments['title'] = 'register';
        return parent::render($arguments);
    }
}
