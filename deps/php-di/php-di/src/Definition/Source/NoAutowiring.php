<?php

declare (strict_types=1);
namespace WpifyMapyCzDeps\DI\Definition\Source;

use WpifyMapyCzDeps\DI\Definition\Exception\InvalidDefinition;
use WpifyMapyCzDeps\DI\Definition\ObjectDefinition;
/**
 * Implementation used when autowiring is completely disabled.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class NoAutowiring implements Autowiring
{
    public function autowire(string $name, ObjectDefinition $definition = null)
    {
        throw new InvalidDefinition(\sprintf('Cannot autowire entry "%s" because autowiring is disabled', $name));
    }
}
