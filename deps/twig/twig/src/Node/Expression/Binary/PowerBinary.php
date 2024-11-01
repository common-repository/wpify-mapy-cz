<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WpifyMapyCzDeps\Twig\Node\Expression\Binary;

use WpifyMapyCzDeps\Twig\Compiler;
class PowerBinary extends AbstractBinary
{
    public function operator(Compiler $compiler) : Compiler
    {
        return $compiler->raw('**');
    }
}
