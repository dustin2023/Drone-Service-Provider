<?php

declare(strict_types=1);

namespace Maurerd\Webentwicklung\View\BlogPost;

use Maurerd\Webentwicklung\View\AbstractView;

/**
 *
 */
class Add extends AbstractView
{
    /**
     * @return string
     */
    protected function getTemplatePath(): string
    {
        return '/view/blogPost/add.html';
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
