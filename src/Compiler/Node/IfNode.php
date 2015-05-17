<?php
/**
 * User: 13leaf
 */
namespace Comos\Tage\Compiler\Node;
use Comos\Tage\Compiler\Token;

/**
 * @package Comos\Tage\Compiler\Node
 */
class IfNode extends AbstractNode
{
    public function __construct(array $tokens, array $childNodes = [])
    {
        parent::__construct($tokens, $childNodes);
    }


    public function compile()
    {
        $ifStatement=sprintf("\nif(%s){\n%s\n}",$this->childNodes['if']->compile(),$this->childNodes['ifBody']->compile());
        $elseIfStatements = '';
        $elseStatement = '';
        if(!empty($this->childNodes['elseIfs'])){
            foreach($this->childNodes['elseIfs'] as $i=>$conditionNode){
                $elseIfStatements.=sprintf("elseif(%s){\n%s\n}",$this->childNodes['elseIfs'][$i]->compile(),$this->childNodes['elseIfBodies'][$i]->compile());
            }
        }
        if(!empty($this->childNodes['elseBody'])){
            $elseStatement=sprintf("else{\n%s\n}",$this->childNodes['elseBody']->compile());
        }
        return $ifStatement . $elseIfStatements . $elseStatement;
    }
}
