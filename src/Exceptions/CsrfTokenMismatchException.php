<?php
/*
 * @Author: 7123
 * @Date: 2025-11-15 19:36:32
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:16:47
 */

namespace Luminode\Core\Exceptions;

class CsrfTokenMismatchException extends \Exception
{
    protected $message = 'CSRF token mismatch.';
}
