<?php

declare(strict_types=1);

namespace Maurerd\Webentwicklung\View\Auth;


use Maurerd\Webentwicklung\View\AbstractView;

/**
 *
 */
class Login extends AbstractView
{
    /**
     * @return string
     */
    protected function getTemplatePath(): string
    {
        return '/view/auth/login.html';
    }

    /**
     * @param array $arguments
     * @return string
     */
    public function render(array $arguments = []): string
    {
        $arguments['title'] = 'Login';
        return parent::render($arguments);
    }
}
