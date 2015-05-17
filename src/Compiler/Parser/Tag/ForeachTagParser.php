<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午8:42
 */
namespace Comos\Tage\Compiler\Parser\Tag;

use Comos\Tage\Compiler\Node\BreakNode;
use Comos\Tage\Compiler\Node\Expression\Operand\ConstantNode;
use Comos\Tage\Compiler\ParseException;
use Comos\Tage\Compiler\Parser\CommonTagNode;
use Comos\Tage\Compiler\Parser\CommonTagParser;
use Comos\Tage\Compiler\Token;
use Comos\Tage\Compiler\TokenStream;

class ForeachTagParser extends CommonTagParser
{
    public function getTagName()
    {
        return 'foreach';
    }

    public function hasCloseTag()
    {
        return true;
    }

    public function getRequiredAttributes()
    {
        return ['from','item'];
    }

    public function parse(TokenStream $tokenStream)
    {
        return parent::parse($tokenStream);
    }


    public function parseStringName($tagName)
    {
        $node = $this->attributes[$tagName];
        if(!$node instanceof ConstantNode){
            throw new ParseException($this->rootParser->getTokenStream()->getFileName(),sprintf('%s expected string token only',$tagName),$this->tagToken->line,$this->tagToken->col);
        }
        $constantToken=$node->tokens['constant'];
        $name=trim($constantToken->getValue(),'"\'');
        return $name;
    }

    public function compile()
    {
        $itemName=$this->parseStringName('item');
        $keyName = '';
        if(isset($this->attributes['key'])){
            $keyName = $this->parseStringName('key');
        }
        $foreachStatement=sprintf("\nforeach(%s as %s $%s){\n%s\n}",
            $this->attributes['from']->compile(),
            empty($keyName)?'':sprintf('$%s=>',$keyName),
            $itemName,
            $this->bodyNode->compile()
        );
        return $foreachStatement;
    }
}