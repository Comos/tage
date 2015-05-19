<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午8:42
 */
namespace Comos\Tage\Compiler\Parser\Tag;

use Comos\Tage\Compiler\Parser\CommonTagParser;

class SetTagParser extends CommonTagParser
{
    public function getTagName()
    {
        return 'set';
    }

    public function compile()
    {
        $declares = [];
        foreach($this->attributes as $name=>$expressionNode){
            $declares[]=sprintf('$%s=%s;',$name,$expressionNode->compile());
        }
        return "\n".join("", $declares);
    }
}