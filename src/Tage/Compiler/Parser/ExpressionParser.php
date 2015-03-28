<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:16
 */
namespace Tage\Compiler\Parser;

use Tage\Compiler\CompileException;
use Tage\Compiler\Compiler\Node\Expression\Operand\ConstantNode;
use Tage\Compiler\Node\Expression\Operand\VarNode;
use Tage\Compiler\Token;
use Tage\Compiler\TokenStream;
use Tage\Compiler\Node\Expression\ExpressionNode;
use Tage\TageException;

/**
 * Class ExpressionParser
 * @package Tage\Parser
 * 解析表达式
 */
class ExpressionParser extends AbstractParser{

    const ORDER_L2R=1;
    const ORDER_R2L=2;

    public static $coreOperators = [];
    public static $coreBinaryOperators=[
        array('op'=>'+','nodeClass'=>'Tage\Compiler\Node\Expression\BinaryNod\AddNode','precedence'=>30,'order'=>self::ORDER_L2R),
        array('op'=>'-','nodeClass'=>'Tage\Compiler\Node\Expression\BinaryNod\SubNode','precedence'=>30,'order'=>self::ORDER_L2R),
        array('op'=>'*','nodeClass'=>'Tage\Compiler\Node\Expression\BinaryNod\MulNode','precedence'=>60,'order'=>self::ORDER_L2R),
        array('op'=>'/','nodeClass'=>'Tage\Compiler\Node\Expression\BinaryNod\DivNode','precedence'=>60,'order'=>self::ORDER_L2R),
    ];


    public function __construct($options=array())
    {

    }

    /**
     * @param $tokenStream TokenStream
     * @return ExpressionNode
     */
    public function parse(TokenStream $tokenStream)
    {
        if($tokenStream->test([Token::TYPE_VARIABLE,Token::TYPE_NUMBER,Token::TYPE_STRING,Token::TYPE_NAME])){
            return $this->parseOperand($tokenStream);
        }else{
            throw new TageException('No implement');
        }
    }

    public function parseOperand(TokenStream $tokenStream)
    {
        $token=$tokenStream->next();
        switch($token->getType()){
            case Token::TYPE_VARIABLE:
                //TODO parse filter or sub
                return new VarNode($token);
            case Token::TYPE_NUMBER:
            case Token::TYPE_STRING:
                return new ConstantNode($token);
            case Token::TYPE_NAME:
                if(in_array(strtolower($token->getValue()),['null','true','false'])){
                    return new ConstantNode($token);
                }else{
                    //TODO parseFunction
                    throw new TageException('no implement');
                    $tokenStream->expect(Token::TYPE_PUNCTUATION,'(');
                }
        }
    }
}