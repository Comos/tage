<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:24
 */
namespace Etag\Node\Expression\Operator\Binary;

use Etag\Node\Expression\BinaryNode;

class AddNode extends BinaryNode
{
    public function compile()
    {
        return sprintf('(%s+%s)',$this->leftNode->compile(),$this->rightNode->compile());
        //return sprintf('etag_add(%s,%s)',$this->leftNode->compile(),$this->rightNode->compile());
    }

}