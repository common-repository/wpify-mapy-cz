<?php

declare (strict_types=1);
namespace WpifyMapyCzDeps\PHPStan\PhpDocParser\Ast\PhpDoc;

use WpifyMapyCzDeps\PHPStan\PhpDocParser\Ast\NodeAttributes;
use WpifyMapyCzDeps\PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
class ExtendsTagValueNode implements PhpDocTagValueNode
{
    use NodeAttributes;
    /** @var GenericTypeNode */
    public $type;
    /** @var string (may be empty) */
    public $description;
    public function __construct(GenericTypeNode $type, string $description)
    {
        $this->type = $type;
        $this->description = $description;
    }
    public function __toString() : string
    {
        return \trim("{$this->type} {$this->description}");
    }
}
