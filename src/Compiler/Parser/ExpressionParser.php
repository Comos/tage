<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:16
 */
namespace Comos\Tage\Compiler\Parser;

use Comos\Tage\Compiler\Node\Expression\Operand\ArrayItemNode;
use Comos\Tage\Compiler\Node\Expression\Operand\ArrayNode;
use Comos\Tage\Compiler\Node\Expression\Operand\AttributeNameNode;
use Comos\Tage\Compiler\Node\Expression\Operand\AttributeNode;
use Comos\Tage\Compiler\Node\Expression\Operand\ConstantNode;
use Comos\Tage\Compiler\Node\Expression\Operand\FilterNode;
use Comos\Tage\Compiler\Node\Expression\Operand\FunctionNode;
use Comos\Tage\Compiler\Node\Expression\Operand\MethodNode;
use Comos\Tage\Compiler\Node\Expression\Operand\VarNode;
use Comos\Tage\Compiler\Node\Expression\Operator\TernaryNode;
use Comos\Tage\Compiler\ParseException;
use Comos\Tage\Compiler\Token;
use Comos\Tage\Compiler\TokenStream;
use Comos\Tage\Compiler\Node\Expression\ExpressionNode;
use Comos\Tage\TageException;

/**
 * Class ExpressionParser
 * @package Tage\Parser
 * 解析表达式
 */
class ExpressionParser extends AbstractParser{

    const ASSOCIATIVITY_L2R='l2r';
    const ASSOCIATIVITY_R2L='r2l';

    const OPERATOR_TYPE_BINARY = 'binary';
    const OPERATOR_TYPE_UNARY = 'unary';

    /**
     * @var TokenStream
     */
    private $tokenStream;

    public static $coreOperators=[
        array('op'=>'+','type'=>self::OPERATOR_TYPE_UNARY,'nodeClass'=>'Comos\Tage\Compiler\Node\Expression\Operator\UnaryNode','precedence'=>500,'associativity'=>self::ASSOCIATIVITY_L2R),
        array('op'=>'-','type'=>self::OPERATOR_TYPE_UNARY,'nodeClass'=>'Comos\Tage\Compiler\Node\Expression\Operator\UnaryNode','precedence'=>500,'associativity'=>self::ASSOCIATIVITY_L2R),
        array('op'=>'!','type'=>self::OPERATOR_TYPE_UNARY,'nodeClass'=>'Comos\Tage\Compiler\Node\Expression\Operator\UnaryNode','precedence'=>500,'associativity'=>self::ASSOCIATIVITY_L2R),
        array('op'=>'not','type'=>self::OPERATOR_TYPE_UNARY,'nodeClass'=>'Comos\Tage\Compiler\Node\Expression\Operator\Unary\NotNode','precedence'=>500,'associativity'=>self::ASSOCIATIVITY_L2R),
        //
        array('op'=>'..','type'=>self::OPERATOR_TYPE_BINARY,'nodeClass'=>'Comos\Tage\Compiler\Node\Expression\Operator\Binary\RangeNode','precedence'=>30,'associativity'=>self::ASSOCIATIVITY_L2R),
        array('op'=>'+','type'=>self::OPERATOR_TYPE_BINARY,'nodeClass'=>'Comos\Tage\Compiler\Node\Expression\Operator\BinaryNode','precedence'=>30,'associativity'=>self::ASSOCIATIVITY_L2R),
        array('op'=>'-','type'=>self::OPERATOR_TYPE_BINARY,'nodeClass'=>'Comos\Tage\Compiler\Node\Expression\Operator\BinaryNode','precedence'=>30,'associativity'=>self::ASSOCIATIVITY_L2R),
        array('op'=>'~','type'=>self::OPERATOR_TYPE_BINARY,'nodeClass'=>'Comos\Tage\Compiler\Node\Expression\Operator\Binary\StringConcatNode','precedence'=>40,'associativity'=>self::ASSOCIATIVITY_L2R),
        array('op'=>'*','type'=>self::OPERATOR_TYPE_BINARY,'nodeClass'=>'Comos\Tage\Compiler\Node\Expression\Operator\BinaryNode','precedence'=>60,'associativity'=>self::ASSOCIATIVITY_L2R),
        array('op'=>'/','type'=>self::OPERATOR_TYPE_BINARY,'nodeClass'=>'Comos\Tage\Compiler\Node\Expression\Operator\BinaryNode','precedence'=>60,'associativity'=>self::ASSOCIATIVITY_L2R),
        array('op'=>'%','type'=>self::OPERATOR_TYPE_BINARY,'nodeClass'=>'Comos\Tage\Compiler\Node\Expression\Operator\BinaryNode','precedence'=>60,'associativity'=>self::ASSOCIATIVITY_L2R),
        array('op'=>'//','type'=>self::OPERATOR_TYPE_BINARY,'nodeClass'=>'Comos\Tage\Compiler\Node\Expression\Operator\Binary\NumericDivNode','precedence'=>60,'associativity'=>self::ASSOCIATIVITY_L2R),
        array('op'=>'^','type'=>self::OPERATOR_TYPE_BINARY,'nodeClass'=>'Comos\Tage\Compiler\Node\Expression\Operator\Binary\PowNode','precedence'=>200,'associativity'=>self::ASSOCIATIVITY_R2L),
        //
        array('op'=>'in','type'=>self::OPERATOR_TYPE_BINARY,'nodeClass'=>'Comos\Tage\Compiler\Node\Expression\Operator\Binary\InNode','precedence'=>20,'associativity'=>self::ASSOCIATIVITY_L2R),
        array('op'=>'>','type'=>self::OPERATOR_TYPE_BINARY,'nodeClass'=>'Comos\Tage\Compiler\Node\Expression\Operator\BinaryNode','precedence'=>20,'associativity'=>self::ASSOCIATIVITY_L2R),
        array('op'=>'<','type'=>self::OPERATOR_TYPE_BINARY,'nodeClass'=>'Comos\Tage\Compiler\Node\Expression\Operator\BinaryNode','precedence'=>20,'associativity'=>self::ASSOCIATIVITY_L2R),
        array('op'=>'>=','type'=>self::OPERATOR_TYPE_BINARY,'nodeClass'=>'Comos\Tage\Compiler\Node\Expression\Operator\BinaryNode','precedence'=>20,'associativity'=>self::ASSOCIATIVITY_L2R),
        array('op'=>'<=','type'=>self::OPERATOR_TYPE_BINARY,'nodeClass'=>'Comos\Tage\Compiler\Node\Expression\Operator\BinaryNode','precedence'=>20,'associativity'=>self::ASSOCIATIVITY_L2R),
        array('op'=>'==','type'=>self::OPERATOR_TYPE_BINARY,'nodeClass'=>'Comos\Tage\Compiler\Node\Expression\Operator\BinaryNode','precedence'=>20,'associativity'=>self::ASSOCIATIVITY_L2R),
        array('op'=>'!=','type'=>self::OPERATOR_TYPE_BINARY,'nodeClass'=>'Comos\Tage\Compiler\Node\Expression\Operator\BinaryNode','precedence'=>20,'associativity'=>self::ASSOCIATIVITY_L2R),
        //
        array('op'=>'&&','type'=>self::OPERATOR_TYPE_BINARY,'nodeClass'=>'Comos\Tage\Compiler\Node\Expression\Operator\BinaryNode','precedence'=>15,'associativity'=>self::ASSOCIATIVITY_L2R),
        array('op'=>'||','type'=>self::OPERATOR_TYPE_BINARY,'nodeClass'=>'Comos\Tage\Compiler\Node\Expression\Operator\BinaryNode','precedence'=>10,'associativity'=>self::ASSOCIATIVITY_L2R),
    ];

    private $_binaryOperators=[];
    private $_unaryOperators=[];

    private $operatorTable;


    public function __construct($options=array())
    {
        foreach(self::$coreOperators as $operatorConf){
            $this->addOperator($operatorConf);
        }
    }

    /**
     * op => 操作符的标识,如'+'
     * nodeClass => 创建的node class全称
     * precedence => 操作符的优先级值，数字类型
     * type => 操作符类型,可以为二元或一元(常量:OPERATOR_TYPE_BINARY,OPERATOR_TYPE_UNARY)
     * associativity => 操作符的结合模式，可为左结合或右结合(常量:ASSOCIATIVITY_L2R,OPERATOR_TYPE_UNARY)
     * @param $conf array
     */
    public function addOperator(array $conf)
    {
        $this->operatorTable[$conf['type']][$conf['op']]=$conf;
        if($conf['type'] == self::OPERATOR_TYPE_BINARY){
            $this->_binaryOperators[] = $conf['op'];
        }else{
            $this->_unaryOperators[] = $conf['op'];
        }
    }

    /**
     * @param $tokenStream TokenStream
     * @return ExpressionNode
     */
    public function parse(TokenStream $tokenStream)
    {
        $this->tokenStream=$tokenStream;
        return $this->parseExp(0);
    }

    protected  function inBinaryOperator($token)
    {
        return $token->getType() == \Comos\Tage\Compiler\Token::TYPE_OPERATOR && in_array($token->getValue(),$this->_binaryOperators);
    }

    protected function inUnaryOperator($token)
    {
        return $token->getType() == \Comos\Tage\Compiler\Token::TYPE_OPERATOR && in_array($token->getValue(),$this->_unaryOperators);
    }

    public function getOperatorConf($type,$op)
    {
        return $this->operatorTable[$type][$op];
    }

    protected function makeBinaryNode($opToken,$left,$right)
    {
        $opConf = $this->getOperatorConf(self::OPERATOR_TYPE_BINARY, $opToken->getValue());
        return new $opConf['nodeClass'](['op'=>$opToken],['left'=>$left,'right'=>$right]);
    }

    protected function makeUnaryNode($opToken,$node)
    {
        $opConf = $this->getOperatorConf(self::OPERATOR_TYPE_UNARY, $opToken->getValue());
        return new $opConf['nodeClass'](['op'=>$opToken],[$node]);
    }

    /**
     * @param $precedence
     * @return ExpressionNode
     * use precedence climbing
     * http://blog.fengwang.org.cn/2015/05/14/%E4%BD%BF%E7%94%A8%E9%80%92%E5%BD%92%E4%B8%8B%E9%99%8D%E5%88%86%E6%9E%90%E8%A1%A8%E8%BE%BE%E5%BC%8F%E8%AF%91%E5%9B%9B/
     */
    public function parseExp($precedence,$parseFilter=true)
    {
        $exp=$this->parsePrimary();
        while($this->inBinaryOperator($this->tokenStream->lookNext())
            && $this->getOperatorConf(self::OPERATOR_TYPE_BINARY,$this->tokenStream->lookNext()->getValue())['precedence']>=$precedence ){
            $opToken=$this->tokenStream->next();
            $opConf=$this->operatorTable[self::OPERATOR_TYPE_BINARY][$opToken->getValue()];
            $right=$this->parseExp($opConf['associativity']==self::ASSOCIATIVITY_L2R?$opConf['precedence']+1:$opConf['precedence']);
            $exp = $this->makeBinaryNode($opToken, $exp, $right);
        }
        return $this->parseTernary($this->parseFilter($exp,$parseFilter));
    }

    public function parseFilter($exp,$parseFilter)
    {
        if(!$parseFilter){
            return $exp;
        }
        while(!$this->tokenStream->isEOF()){
            if(!$this->tokenStream->test(Token::TYPE_PUNCTUATION,'|')){
                break;
            }
            $this->tokenStream->next();
            $funcNameToken=$this->tokenStream->expect(Token::TYPE_NAME);
            $argNodes=[$exp];
            while($this->tokenStream->test(Token::TYPE_OPERATOR,':')){
                $this->tokenStream->next();
                $argNodes[] = $this->parseExp(0, false);
            }
            $exp=new FilterNode(['filterName'=>$funcNameToken],$argNodes);
        }
        return $exp;
    }

    /**
     * to parse ternary:exp?exp:exp
     * @param $exp
     * @return TernaryNode
     */
    public function parseTernary($exp)
    {
        if($this->tokenStream->test(Token::TYPE_OPERATOR,'?')){
            $startToken=$this->tokenStream->next();
            $ifBodyNode=$this->parseExp(0);
            $this->tokenStream->expect(Token::TYPE_OPERATOR, ':');
            $elseBodyNode = $this->parseExp(0);
            return new TernaryNode([$startToken],['if'=>$exp,'ifBody'=>$ifBodyNode,'elseBody'=>$elseBodyNode]);
        }
        return $exp;
    }

    public function parseUnary()
    {
        $opToken=$this->tokenStream->next();
        $exp=$this->parsePrimary();
        return $this->makeUnaryNode($opToken, $exp);
    }

    public function parseBracket()
    {
        $this->tokenStream->next();
        $node = $this->parseExp(0,true);
        $this->tokenStream->expect(Token::TYPE_PUNCTUATION, ')');
        return $node;
    }

    public function parseArray()
    {
        $arrayToken=$this->tokenStream->next();
        $arrayNodes=[];
        $endPunctuation = $arrayToken->value=='['?']':'}';
        while(!$this->tokenStream->isEOF()
            && !$this->tokenStream->test(Token::TYPE_PUNCTUATION,$endPunctuation)) {
            $node = $this->parseExp(0);
            if($this->tokenStream->test(Token::TYPE_OPERATOR,':')){
                $this->tokenStream->next();
                //XXX check keyNode constant?
                $valueNode = $this->parseExp(0);
                $arrayNodes[] = new ArrayItemNode([$arrayToken], ['key'=>$node,'value'=>$valueNode]);
            }else{
                $arrayNodes[]=new ArrayItemNode([$arrayToken],['value'=>$node]);
            }
            if($this->tokenStream->test(Token::TYPE_PUNCTUATION,',')){
                $this->tokenStream->next();
            }
        }
        $this->tokenStream->expect(Token::TYPE_PUNCTUATION, $endPunctuation);
        return new ArrayNode([$arrayToken],$arrayNodes);
    }

    public function parseFunction()
    {
        $funcNameToken = $this->tokenStream->expect(Token::TYPE_NAME);
        $this->tokenStream->expect(Token::TYPE_PUNCTUATION,'(');
        $argNodes=[];
        while(!$this->tokenStream->isEOF()){
            if($this->tokenStream->test(Token::TYPE_PUNCTUATION,')')){
                break;
            }
            $argNodes[] = $this->parseExp(0);
            if(!$this->tokenStream->test(Token::TYPE_PUNCTUATION,')')){
                $this->tokenStream->expect(Token::TYPE_PUNCTUATION, ',');
            }
        }
        $this->tokenStream->expect(Token::TYPE_PUNCTUATION, ')');
        return new FunctionNode(['funcName'=>$funcNameToken],$argNodes);
    }

    /**
     */
    public function parseAttributeOrMethod()
    {
        $exprToken = $this->tokenStream->expect(Token::TYPE_VARIABLE);//start by variable
        $exprNode=new VarNode($exprToken);
        while(!$this->tokenStream->isEOF()){
            if($this->tokenStream->test(Token::TYPE_PUNCTUATION,'.')){
                $this->tokenStream->next();
                //not function
                if($this->tokenStream->test(Token::TYPE_NAME) && $this->tokenStream->lookNext(2)->value != '('){
                    $nameToken=$this->tokenStream->next();
                    $rightNode=new AttributeNameNode(['name'=>$nameToken]);
                    $exprNode=new AttributeNode([$nameToken],['left'=>$exprNode,'right'=>$rightNode]);
                }else{
                    $rightNode=$this->parseFunction();
                    $exprNode=new MethodNode([$rightNode->tokens['funcName']],['left'=>$exprNode,'right'=>$rightNode]);
                }
            }else if($this->tokenStream->test(Token::TYPE_PUNCTUATION,'[')){
                $this->tokenStream->next();
                $rightNode=$this->parsePrimary();
                $exprNode = new AttributeNode([$exprToken],['left'=>$exprNode,'right'=>$rightNode]);
                $this->tokenStream->expect(Token::TYPE_PUNCTUATION, ']');
            }else{
                break;
            }
        }
        return $exprNode;
    }

    public function parsePrimary()
    {
        //unary
        if($this->inUnaryOperator($this->tokenStream->lookNext())){
            return $this->parseUnary();
        }
        //bracket
        if($this->tokenStream->test(Token::TYPE_PUNCTUATION,'(')){
            return $this->parseBracket();
        }
        //array
        if($this->tokenStream->test(Token::TYPE_PUNCTUATION,'[') || $this->tokenStream->test(Token::TYPE_PUNCTUATION,'{')){
            return $this->parseArray();
        }
        if($this->tokenStream->test(Token::TYPE_NAME)){
            //constant val eg: null
            if(in_array(strtolower($this->tokenStream->lookNext()->getValue()),['null','true','false'])){
                return new ConstantNode($this->tokenStream->next());
            }else{
                //function eg: test()
                return $this->parseFunction();
            }
        }
        //attribute eg: $x | $x.varName | $x.func(1,2) | $x["y"]
        if($this->tokenStream->test(Token::TYPE_VARIABLE)){
            return $this->parseAttributeOrMethod();
        }
        $token=$this->tokenStream->next();
        switch($token->getType()){
            case Token::TYPE_NUMBER:
            case Token::TYPE_STRING:
                return new ConstantNode($token);
            default:
                throw new ParseException($this->tokenStream->getFileName(),'unexpected '.$token->getValue(),$token->line,$token->col);
        }
    }
}