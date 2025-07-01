<?php

namespace App\Container;

use Psr\Container\NotFoundExceptionInterface;

class ServiceNotFoundException extends \Exception implements NotFoundExceptionInterface
{

}