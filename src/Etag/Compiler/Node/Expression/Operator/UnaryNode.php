<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:31
 */
namespace Etag\Compiler\Node\Expression;

use Etag\Compiler\Node\AbstractNode;

class UnaryNode extends AbstractNode
{
    /**
     * @var AbstractNode
     */
    protected $childNode;
}