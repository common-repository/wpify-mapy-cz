<?php

declare (strict_types=1);
namespace WpifyMapyCzDeps\DI;

use WpifyMapyCzDeps\Psr\Container\ContainerExceptionInterface;
/**
 * Exception for the Container.
 */
class DependencyException extends \Exception implements ContainerExceptionInterface
{
}
