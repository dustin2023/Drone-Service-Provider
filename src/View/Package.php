<?php

namespace Maurerd\Webentwicklung\View;

class Package extends AbstractView
{

    protected function getTemplatePath(): string
    {
        return '/view/package.html';
    }
    /**
     * @param array $arguments
     * @return string
     */
    public function render(array $arguments = []): string
    {
        $arguments['title'] = 'Packet wählen';
        return parent::render($arguments);
    }
}