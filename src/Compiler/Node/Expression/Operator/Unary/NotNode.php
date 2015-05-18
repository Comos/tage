<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:29
 */
namespace Comos\Tage\Compiler\Node\Expression\Operator\Unary;

use Comos\Tage\Compiler\Node\Expression\Operator\UnaryNode;

class NotNode extends UnaryNode
{
    public function compile()
    {
        return sprintf('(!%s)',$this->childNode->compile());
    }
}