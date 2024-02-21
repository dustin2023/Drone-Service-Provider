<?php

declare(strict_types=1);

namespace Maurerd\Webentwicklung\View;

abstract class AbstractView
{
    protected const TEMPLATE_PLACEHOLDER_HEADER = '{{header}}';
    protected const TEMPLATE_PLACEHOLDER_CONTENT = '{{content}}';
    protected const TEMPLATE_PLACEHOLDER_FOOTER = '{{footer}}';

    abstract protected function getTemplatePath(): string;

    /**
     * @return string
     */
    protected function getBaseTemplatePath(): string
    {
        return '/view/base.html';
    }

    protected function getHeaderTemplatePath(): string
    {
        return '/view/components/header.html';
    }
    protected function getFooterTemplatePath(): string
    {
        return '/view/components/footer.html';
    }

    /**
     * @param array $arguments
     * @return string
     */
    public function render(array $arguments = []): string
    {
        extract($arguments, EXTR_OVERWRITE);
        ob_start();
        require dirname(__DIR__, 2) . $this->getBaseTemplatePath();
        $baseTemplate = ob_get_clean();
        ob_start();
        require dirname(__DIR__, 2) . $this->getTemplatePath();
        $contentTemplate = ob_get_clean();
        ob_start();
        require dirname(__DIR__, 2) . $this->getHeaderTemplatePath();
        $headerTemplate = ob_get_clean();
        ob_start();
        require dirname(__DIR__, 2) . $this->getFooterTemplatePath();
        $footerTemplate = ob_get_clean();

        $placeholders = [static::TEMPLATE_PLACEHOLDER_HEADER => $headerTemplate, static::TEMPLATE_PLACEHOLDER_CONTENT => $contentTemplate, static::TEMPLATE_PLACEHOLDER_FOOTER => $footerTemplate];

        return strtr($baseTemplate, $placeholders);
    }
}