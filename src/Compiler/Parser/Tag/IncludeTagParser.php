<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午8:42
 */
namespace Comos\Tage\Compiler\Parser\Tag;

use Comos\Tage\Compiler\Node\IncludeNode;
use Comos\Tage\Compiler\Token;
use Comos\Tage\Compiler\TokenStream;
use Comos\Tage\Compiler\Parser\TagParser;

/**
 * Class IncludeTagParser
 * @package Comos\Tage\Compiler\Parser\Tag
 */
class IncludeTagParser extends TagParser
{
    public function getTagName()
    {
        return 'include';
    }

    public function parse(TokenStream $tokenStream)
    {
        $pathNode = $this->getExpressionParser()->parse($tokenStream);
        $withNode=null;
        if($tokenStream->test(Token::TYPE_NAME,'with')){
            $tokenStream->next();
            $withNode=$this->getExpressionParser()->parseArray();
        }
        $tokenStream->expect(Token::TYPE_TAG_END);
        return new IncludeNode(['tag'=>$this->tagToken],['path'=>$pathNode,'with'=>$withNode]);
    }
}