<?php

namespace Luminode\Core\Exceptions;

class CsrfTokenMismatchException extends \Exception
{
    protected $message = 'CSRF token mismatch.';
}
