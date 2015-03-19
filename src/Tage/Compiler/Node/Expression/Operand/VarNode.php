<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:34
 */
namespace Tage\Compiler\Node\Expression\Operand;

use Tage\Compiler\Node\AbstractNode;
use Tage\Compiler\Token;

class VarNode extends AbstractNode
{
    public function __construct(Token $varToken)
    {
        parent::__construct(['var'=>$varToken]);
    }

    public function compile()
    {
        return sprintf('(%s)',$this->tokens['var']->getValue());
    }


}
