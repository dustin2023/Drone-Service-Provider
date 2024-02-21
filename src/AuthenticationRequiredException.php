<?php

declare(strict_types=1);

namespace Maurerd\Webentwicklung;

class AuthenticationRequiredException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'Aktiver Login benötigt';

    /**
     * @var int
     */
    protected $code = 401;
}
