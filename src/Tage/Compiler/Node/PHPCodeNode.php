<?php
/**
 * User: 13leaf
 */
namespace Tage\Compiler\Node;
use Tage\Compiler\Token;

/**
 * @package Tage\Compiler\Node
 */
class PHPCodeNode extends AbstractNode
{
    public function __construct(Token $phpToken)
    {
        parent::__construct(['php'=>$phpToken]);
    }


    public function compile()
    {
        $phpToken=$this->tokens['php'];
        $phpCode=$phpToken->getValue();
        if(!preg_match('/.*\?\>\s*/',$phpCode)){
            $phpCode.=" ?>";
        }
        return $phpCode;
    }
}
