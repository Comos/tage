<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:30
 */
namespace Etag\Compiler\Node\Expression;

use Etag\Compiler\Node\AbstractNode;

class BinaryNode extends AbstractNode
{
    /**
     * @var AbstractNode
     */
    protected $leftNode;
    /**
     * @var AbstractNode
     */
    protected $rightNode;
}
