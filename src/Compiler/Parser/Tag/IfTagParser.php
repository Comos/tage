<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午8:42
 */
namespace Comos\Tage\Compiler\Parser\Tag;

use Comos\Tage\Compiler\Node\IfNode;
use Comos\Tage\Compiler\Parser\TagParser;
use Comos\Tage\Compiler\Token;
use Comos\Tage\Compiler\TokenStream;

class IfTagParser extends TagParser
{
    private $ifConditionNode;
    private $ifBodyNode;
    private $elseIfConditionNodeList;
    private $elseIfBodyNodesList;
    private $elseBodyNode;

    public function getTagName()
    {
        return 'if';
    }

    public function parse(TokenStream $tokenStream)
    {
        $this->ifConditionNode = $this->getExpressionParser()->parse($tokenStream);
        $tokenStream->expect(Token::TYPE_TAG_END);
        $this->ifBodyNode= $this->rootParser->parseBody($this);
        return new IfNode([],[
            'if'=>$this->ifConditionNode,
            'ifBody'=>$this->ifBodyNode,
            'elseIfs'=>$this->elseIfConditionNodeList,
            'elseIfBodies'=>$this->elseIfBodyNodesList,
            'elseBody'=>$this->elseBodyNode
        ]);
    }

    public function hasCloseTag()
    {
        return true;
    }

    public function parseTagBreak(TokenStream $tokenStream)
    {
        if($tokenStream->test(Token::TYPE_NAME,'elseif')){
            $token=$tokenStream->next();
            //XXX fix to check elseif after else
            $this->elseIfConditionNodeList[] = $this->getExpressionParser()->parse($tokenStream);
            $tokenStream->expect(Token::TYPE_TAG_END);
            $this->elseIfBodyNodesList[] = $this->rootParser->parseBody($this);
            return true;
        }
        if($tokenStream->test(Token::TYPE_NAME,'else')){
            $tokenStream->next();
            $tokenStream->expect(Token::TYPE_TAG_END);
            $this->elseBodyNode = $this->rootParser->parseBody($this);
            return true;
        }
        return parent::parseTagBreak($tokenStream);
    }


}