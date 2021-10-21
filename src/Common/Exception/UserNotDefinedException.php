<?php

namespace App\Common\Exception;

/**
 * @package App\Common\Exception
 */
class UserNotDefinedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('User is not yet defined');
    }
}
