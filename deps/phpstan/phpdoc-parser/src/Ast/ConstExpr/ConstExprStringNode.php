<?php

declare (strict_types=1);
namespace WpifyMapyCzDeps\PHPStan\PhpDocParser\Ast\ConstExpr;

use WpifyMapyCzDeps\PHPStan\PhpDocParser\Ast\NodeAttributes;
class ConstExprStringNode implements ConstExprNode
{
    use NodeAttributes;
    /** @var string */
    public $value;
    public function __construct(string $value)
    {
        $this->value = $value;
    }
    public function __toString() : string
    {
        return $this->value;
    }
}
