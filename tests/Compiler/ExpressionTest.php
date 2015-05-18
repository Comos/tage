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
            ['not $b','(!($b))'],
            ['not not -$b','(!(!(-($b))))'],
            //binary
            ['$a+$b','(($a)+($b))'],
            ['$a-$b','(($a)-($b))'],
            ['$a*$b','(($a)*($b))'],
            ['$a/$b','(($a)/($b))'],
            ['$a%$b','(($a)%($b))'],
            ['5//4','floor((5)/(4))'],
            ['"a"~"b"','(("a").("b"))'],

            ['2..10','range((2),(10))'],
            ['$x in $arr','in_array(($x),($arr))'],
            ['$a>$b','(($a)>($b))'],
            ['$a>=$b','(($a)>=($b))'],
            ['3==5','((3)==(5))'],
            ['3!=5','((3)!=(5))'],
            ['2^3^4','pow((2),pow((3),(4)))'],

            ['$a+$b+1','((($a)+($b))+(1))'],
            ['$a+-$b+1','((($a)+(-($b)))+(1))'],
            ['3+4*5','((3)+((4)*(5)))'],
            ['(3+4)/6','(((3)+(4))/(6))'],
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
