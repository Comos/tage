<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午5:20
 */
namespace Comos\Tage\Compiler\Parser;

use Comos\Tage\Compiler\Node\BodyNode;
use Comos\Tage\Compiler\Node\PHPCodeNode;
use Comos\Tage\Compiler\Node\PrintNode;
use Comos\Tage\Compiler\Node\RootNode;
use Comos\Tage\Compiler\ParseException;
use Comos\Tage\Compiler\Token;
use Comos\Tage\Compiler\TokenStream;
use Comos\Tage\Compiler\Node\AbstractNode;
use Comos\Tage\TageException;

/**
 * @package Comos\Tage\Parser
 * 解析标签语句
 */
class Parser extends AbstractParser{

    /**
     * @var TagParser[]
     */
    protected $registerTags=[];
    private static $coreTags=null;

    /**
     * @var ExpressionParser parser
     */
    private $expressionParser=null;

    /**
     * @var TokenStream
     */
    private $tokenStream;

    /**
     * @param $options array
     * customTagParsers: 自定义标签解析，key:tagName,value:TagParser实例
     */
    public function __construct(array $options=[])
    {
        $customTags = isset($options['customTagParsers']) ? $options['customTagParsers'] : [];
        if(self::$coreTags === null){
            self::$coreTags=[];
            //scan tag directory
            foreach(glob(__DIR__.'/Tag/*TagParser.php') as $coreTag)
            {
                $tagClass='Comos\Tage\Compiler\Parser\Tag'.'\\'.basename($coreTag,".php");
                /**
                 * @var TagParser $tagParser
                 */
                $tagParser=new $tagClass();
                self::$coreTags[$tagParser->getTagName()]=$tagParser;
            }
        }
        $this->registerTags = array_merge($customTags, self::$coreTags);
    }


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
     * @return TokenStream
     */
    public function getTokenStream()
    {
        return $this->tokenStream;
    }

    /**
     * @param $fromTagParser null|TagParser
     */
    public function parseBody($fromTagParser)
    {
        $expressionParser=$this->getExpressionParser();
        $nodes=[];
        $tokenStream=$this->tokenStream;

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
                    if($fromTagParser && $fromTagParser->parseTagBreak($tokenStream)){
                        return new BodyNode([],$nodes);
                    }
                    //try parse tag
                    $parseTag=false;
                    $afterNextToken = $tokenStream->lookNext(2);
                    foreach($this->registerTags as $tagName=>$tagParser){
                        if($tokenStream->test(Token::TYPE_NAME,$tagName) && $afterNextToken->type!=Token::TYPE_PUNCTUATION){
                            $tokenStream->next();
                            $tagParser->setRootParser($this);
                            $tagParser->setTagToken($tokenStream->current());
                            $nodes[]=$tagParser->parse($tokenStream);
                            $parseTag=true;
                            break;
                        }
                    }
                    if(!$parseTag){
                        //parse as expression
                        $nodes[]=new PrintNode($expressionParser->parse($tokenStream));
                        $tokenStream->expect(Token::TYPE_TAG_END);
                    }
                    break;
            }
        }
        //check tagParser closed
        if($fromTagParser!=null && !$fromTagParser->hasClosed()){
            throw new ParseException($tokenStream->getFileName(),sprintf('Tag %s is not closed!',$fromTagParser->getTagName()),$fromTagParser->getTagToken()->line,$fromTagParser->getTagToken()->col);
        }
        return new BodyNode([],$nodes);
    }

    /**
     * @param $tokenStream TokenStream
     * @return AbstractNode
     */
    public function parse(TokenStream $tokenStream)
    {
        $this->tokenStream=$tokenStream;
        $bodyNode = $this->parseBody(null);
        return new RootNode($tokenStream->getFileName(),$bodyNode->childNodes);
    }
}
