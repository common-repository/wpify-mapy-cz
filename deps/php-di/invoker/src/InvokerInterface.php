<?php

declare (strict_types=1);
namespace WpifyMapyCzDeps\Invoker;

use WpifyMapyCzDeps\Invoker\Exception\InvocationException;
use WpifyMapyCzDeps\Invoker\Exception\NotCallableException;
use WpifyMapyCzDeps\Invoker\Exception\NotEnoughParametersException;
/**
 * Invoke a callable.
 */
interface InvokerInterface
{
    /**
     * Call the given function using the given parameters.
     *
     * @param callable|array|string $callable Function to call.
     * @param array $parameters Parameters to use.
     * @return mixed Result of the function.
     * @throws InvocationException Base exception class for all the sub-exceptions below.
     * @throws NotCallableException
     * @throws NotEnoughParametersException
     */
    public function call($callable, array $parameters = []);
}
