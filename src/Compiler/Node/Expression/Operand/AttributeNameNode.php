<?php
/**
 * User: wangfeng
 * Date: 15-5-21
 * Time: 下午2:14
 */
namespace Comos\Tage\Compiler\Node\Expression\Operand;

use Comos\Tage\Compiler\Node\AbstractNode;

/**
 * Class AttributeNameNode
 * @package Comos\Tage\Compiler\Node\Expression\Operand
 */
class AttributeNameNode extends AbstractNode
{

    public function __construct(array $tokens, array $childNodes = [])
    {
        parent::__construct($tokens, $childNodes);
    }

    public function compile()
    {
        return sprintf('"%s"',$this->tokens['name']->value);
    }

}