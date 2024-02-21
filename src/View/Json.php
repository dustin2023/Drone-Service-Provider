<?php

declare(strict_types=1);

namespace Maurerd\Webentwicklung\View;

/**
 *
 */
class Json
{
    /**
     * @param array|object $arguments
     * @return string
     * @throws \JsonException
     */
    public function render(array|object $arguments = []): string
    {
        return json_encode($arguments, JSON_THROW_ON_ERROR);
    }
}
