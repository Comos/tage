<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:31
 */
namespace Tage\Compiler\Node\Expression;

use Tage\Compiler\Node\AbstractNode;

class UnaryNode extends AbstractNode
{
    /**
     * @var AbstractNode
     */
    protected $childNode;
}