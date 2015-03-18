<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午5:20
 */
namespace Tage\Compiler\Parser;

use Tage\Compiler\Node\PHPCodeNode;
use Tage\Compiler\Node\PrintNode;
use Tage\Compiler\Node\RootNode;
use Tage\Compiler\Token;
use Tage\Compiler\TokenStream;
use Tage\Compiler\Node\AbstractNode;
use Tage\TageException;

/**
 * Class TagParser
 * @package Tage\Parser
 * 解析标签语句
 */
class TagParser extends AbstractParser{

    /**
     * @var ExpressionParser parser
     */
    private $expressionParser=null;


    public function setExpressionParser(ExpressionParser $parser)
    {
        $this->expressionParser=$parser;
    }

    public function getExpressionParser()
    {
        if($this->expressionParser === null){
            $this->expressionParser = new ExpressionParser();
        }
        return $this->expressionParser;
    }

    /**
     * @param $tokenStream TokenStream
     * @return AbstractNode
     */
    public function parse(TokenStream $tokenStream)
    {
        $expressionParser=$this->getExpressionParser();
        $nodes=[];
        while(!$tokenStream->isEOF()){
            $token=$tokenStream->next();
            switch($token->getType()){
                case Token::TYPE_TEXT:
                    $nodes[] = new PrintNode(null,$token);
                    break;
                case Token::TYPE_PHP_CODE:
                    $nodes[] = new PHPCodeNode($token);
                    break;
                case Token::TYPE_TAG_START:
                    if($tokenStream->test(Token::TYPE_NAME)){
                        //parse tag
                        throw new TageException('no implement');
                    }else{
                        $nodes[]=new PrintNode($expressionParser->parse($tokenStream));
                        $tokenStream->expect(Token::TYPE_TAG_END);
                    }
                    break;
            }
        }
        return new RootNode($tokenStream->getFileName(),$nodes);
    }
}
