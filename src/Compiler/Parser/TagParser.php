<?php
namespace Comos\Tage\Compiler\Parser;

use Comos\Tage\Compiler\Node\AbstractNode;
use Comos\Tage\Compiler\Token;
use Comos\Tage\Compiler\TokenStream;

abstract class TagParser extends AbstractParser
{
    protected $rootParser;
    protected $isClosed=true;

    /**
     * @var Token
     */
    protected $tagToken;

    public function __construct()
    {
        if($this->hasCloseTag()){
            $this->isClosed=false;
        }
    }

    public function setRootParser(Parser $rootParser)
    {
        $this->rootParser=$rootParser;
    }

    public function setTagToken($tagToken)
    {
        $this->tagToken=$tagToken;
    }

    public function getTagToken()
    {
        return $this->tagToken;
    }

    public function getExpressionParser()
    {
        return $this->rootParser->getExpressionParser();
    }

    abstract public function getTagName();

    public function hasClosed()
    {
       return $this->isClosed;
    }

    public function hasCloseTag()
    {
        return false;
    }

    /**
     * @return bool 若标签到达关闭，返回true，否则false
     */
    public function parseTagBreak(TokenStream $tokenStream){
        $endTagName = 'end' . $this->getTagName();
        if($tokenStream->test(Token::TYPE_NAME,$endTagName)){
            $tokenStream->next();
            $tokenStream->expect(Token::TYPE_TAG_END);
            $this->isClosed=true;
            return true;
        }else{
            return false;
        }
    }
}
