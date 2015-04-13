<?php
/**
 * User: bigbigant
 * Date: Mar 11 2015
 */
namespace Comos\Tage\Compiler;

use Comos\Tage\Compiler\Token;

class TokenTest extends \PHPUnit_Framework_Testcase
{

    /**
     * @dataProvider typeToStringProvider
     */
    public function testTypeToString($type, $string)
    {
        $this->assertEquals($string, Token::typeToString($type));
    }

    public function typeToStringProvider()
    {
        return [
            [Token::TYPE_EOF, 'TYPE_EOF'],
        	[Token::TYPE_NAME, 'TYPE_NAME'],
        	[Token::TYPE_NUMBER, 'TYPE_NUMBER'],
        ];
    }
}