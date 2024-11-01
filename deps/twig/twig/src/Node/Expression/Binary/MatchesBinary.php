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
class MatchesBinary extends AbstractBinary
{
    public function compile(Compiler $compiler) : void
    {
        $compiler->raw('WpifyMapyCzDeps\twig_matches(')->subcompile($this->getNode('right'))->raw(', ')->subcompile($this->getNode('left'))->raw(')');
    }
    public function operator(Compiler $compiler) : Compiler
    {
        return $compiler->raw('');
    }
}
