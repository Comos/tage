<?php
/**
 * User: wangfeng
 * Date: 15-5-17
 * Time: 上午11:49
 */
namespace Comos\Tage\Tests\Compiler;
use Comos\Tage\Tests\TageTestCase;

class ExpressionTest extends TageTestCase
{
    public function expressionProvider()
    {
        return [
            //primary
            ['123','(123)'],
            ['456.789','(456.789)'],
            ['$a','($a)'],
            ['true','(true)'],
            ['false','(false)'],
            ['null','(null)'],
            //unary
            ['-123','(-(123))'],
            ['+123.45','(+(123.45))'],
            ['--$a','(-(-($a)))'],
            //binary
            ['$a+$b','(($a)+($b))'],
            ['$a+$b+1','((($a)+($b))+(1))'],
            ['$a+-$b+1','((($a)+(-($b)))+(1))'],
            ['3+4*5','((3)+((4)*(5)))'],
            ['(3+4)/6','(((3)+(4))/(6))'],
            ['$a>$b','(($a)>($b))'],
            ['$a>=$b','(($a)>=($b))'],
            ['3==5','((3)==(5))'],
            ['3!=5','((3)!=(5))'],
        ];
    }

    /**
     * @dataProvider expressionProvider
     */
    public function testExpression($exp,$compileResult)
    {
        $tpl='{{'.$exp.'}}';
        $lexer=new \Comos\Tage\Compiler\Lexer();
        $tokenStream=$lexer->lex($tpl);
        $tokenStream->next();//skip {{
        $expressionParser = new \Comos\Tage\Compiler\Parser\ExpressionParser();
        $expressionNode=$expressionParser->parse($tokenStream);
        $this->assertEquals($compileResult, $expressionNode->compile());
    }
}
