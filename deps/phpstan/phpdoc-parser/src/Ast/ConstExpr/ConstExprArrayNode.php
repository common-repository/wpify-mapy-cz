<?php

declare (strict_types=1);
namespace WpifyMapyCzDeps\PHPStan\PhpDocParser\Ast\ConstExpr;

use WpifyMapyCzDeps\PHPStan\PhpDocParser\Ast\NodeAttributes;
class ConstExprArrayNode implements ConstExprNode
{
    use NodeAttributes;
    /** @var ConstExprArrayItemNode[] */
    public $items;
    /**
     * @param ConstExprArrayItemNode[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }
    public function __toString() : string
    {
        return '[' . \implode(', ', $this->items) . ']';
    }
}
