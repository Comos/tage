<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:27
 */
namespace Tage\Compiler\Compiler\Node\Expression\Operand;

use Tage\Compiler\Node\AbstractNode;
use Tage\Compiler\Token;

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