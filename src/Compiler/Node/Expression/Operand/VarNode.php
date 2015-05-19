<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:34
 */
namespace Comos\Tage\Compiler\Node\Expression\Operand;

use Comos\Tage\Compiler\Node\AbstractNode;
use Comos\Tage\Compiler\Token;

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
