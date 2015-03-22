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
 * @package Tage\Parser
 * 解析标签语句
 */
class Parser extends AbstractParser{

    /**
     * @var TagParser[]
     */
    protected $registerTags=[];
    private static $coreTags=null;

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
                $tagClass='Tage\Compiler\Parser\Tag'.'\\'.basename($coreTag,".php");
                /**
                 * @var TagParser $tagParser
                 */
                $tagParser=new $tagClass();
                self::$coreTags[$tagParser->getTagName()]=$tagParser;
            }
        }
        $this->registerTags = array_merge($customTags, self::$coreTags);
    }


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
                    //try parse tag
                    $parseTag=false;
                    foreach($this->registerTags as $tagName=>$tagParser){
                        if($tokenStream->test(Token::TYPE_NAME,$tagName)){
                            $tagParser->parse($tokenStream);
                            $parseTag=true;
                            break;
                        }
                    }
                    if(!$parseTag){
                        //parse as expression
                        $nodes[]=new PrintNode($expressionParser->parse($tokenStream));
                    }
                    $tokenStream->expect(Token::TYPE_TAG_END);
                    break;
            }
        }
        return new RootNode($tokenStream->getFileName(),$nodes);
    }
}
