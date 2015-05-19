<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午8:42
 */
namespace Comos\Tage\Compiler\Parser;

use Comos\Tage\Compiler\ParseException;
use Comos\Tage\Compiler\Token;
use Comos\Tage\Compiler\TokenStream;
use Comos\Tage\Exception;

class CommonTagParser extends TagParser
{
    //name=>expressionNode
    /**
     * @var \Comos\Tage\Compiler\Node\Expression\ExpressionNode[]
     */
    protected  $attributes=[];
    /**
     * @var \Comos\Tage\Compiler\Node\Expression\ExpressionNode
     */
    protected $bodyNode=null;

    public function getTagName()
    {
        return '_common';
    }

    public function getRequiredAttributes()
    {
        return [];
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function parse(TokenStream $tokenStream)
    {
        while(!$tokenStream->isEOF() && $tokenStream->lookNext()->getType() != Token::TYPE_TAG_END){
            $paramNameToken=$tokenStream->expect(Token::TYPE_NAME);
            $tokenStream->expect(Token::TYPE_OPERATOR, '=');
            $expressionNode=$this->getExpressionParser()->parse($tokenStream);
            $this->attributes[$paramNameToken->getValue()]=$expressionNode;
        }
        $tokenStream->expect(Token::TYPE_TAG_END);
        foreach($this->getRequiredAttributes() as $attributeName){
            if(!isset($this->attributes[$attributeName])){
                throw new ParseException($tokenStream->getFileName(),sprintf('%s require %s attribute',$this->getTagName(),$attributeName),$this->tagToken->line,$this->tagToken->col);
            }
        }
        if($this->hasCloseTag()){
            $this->bodyNode = $this->rootParser->parseBody($this);
        }
        return new CommonTagNode($this);
    }

    public function compile()
    {
        throw new Exception('no implement');
    }
}