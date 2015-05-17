<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:27
 */
namespace Comos\Tage\Compiler\Node\Expression\Operand;

use Comos\Tage\Compiler\Node\AbstractNode;
use Comos\Tage\Compiler\Token;

class ConstantNode extends AbstractNode
{
    public function __construct(Token $constantToken){
        parent::__construct(['constant' => $constantToken]);
    }

    public function compile()
    {
        return sprintf('(%s)',$this->tokens['constant']->getValue());
    }

}