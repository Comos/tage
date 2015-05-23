<?php
/**
 * User: wangfeng
 * Date: 15-5-21
 * Time: 下午2:14
 */
namespace Comos\Tage\Compiler\Node\Expression\Operand;

use Comos\Tage\Compiler\Node\AbstractNode;

/**
 * Class AttributeNode
 * @package Comos\Tage\Compiler\Node\Expression\Operand
 */
class AttributeNode extends AbstractNode
{
    public function __construct(array $tokens, array $childNodes = [])
    {
        parent::__construct($tokens, $childNodes);
    }

    public function compile()
    {
        return sprintf('$this->getAttribute(%s,%s)',$this->childNodes['left']->compile(),$this->childNodes['right']->compile());
    }

}