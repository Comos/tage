<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:24
 */
namespace Comos\Tage\Compiler\Node\Expression\Operator\Binary;

use Comos\Tage\Compiler\Node\Expression\Operator\BinaryNode;

class InNode extends BinaryNode
{
    public function compile()
    {
        return sprintf('in_array(%s,%s)',$this->leftNode->compile(),$this->rightNode->compile());
    }

}