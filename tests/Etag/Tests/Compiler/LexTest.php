<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午5:28
 */
namespace Etag\Tests\Compiler;

use Etag\Compiler\Lexer;
use Etag\Compiler\Token;
use Etag\Tests\EtagTestCase;
use Etag\Tests\TokenBuilder;

class LexTest extends EtagTestCase
{
    public function lexProvider()
    {
        return array(
            ['<html></html>',TokenBuilder::begin()->append(new Token(Token::TYPE_TEXT,'<html></html>',1,1))->build()],
            [' {{$foo}}',TokenBuilder::begin()->append(new Token(Token::TYPE_TEXT,' ',1 , 1))->append(new Token(Token::TYPE_TAG_START,'{{',1,2))->append(new Token(Token::TYPE_VARIABLE,'$foo',1,4))->append(new Token(Token::TYPE_TAG_END,'}}',1,8))->build()],
            [' {{ $foo       }}',TokenBuilder::begin()->append(new Token(Token::TYPE_TEXT,' ',1 , 1))->append(new Token(Token::TYPE_TAG_START,'{{',1,2))->append(new Token(Token::TYPE_VARIABLE,'$foo',1,5))->append(new Token(Token::TYPE_TAG_END,'}}',1,16))->build()],
            [" {{ \$foo\n}}",TokenBuilder::begin()->append(new Token(Token::TYPE_TEXT,' ',1 , 1))->append(new Token(Token::TYPE_TAG_START,'{{',1,2))->append(new Token(Token::TYPE_VARIABLE,'$foo',1,5))->append(new Token(Token::TYPE_TAG_END,'}}',2,1))->build()],
            [" {{ \$foo\r\n}}",TokenBuilder::begin()->append(new Token(Token::TYPE_TEXT,' ',1 , 1))->append(new Token(Token::TYPE_TAG_START,'{{',1,2))->append(new Token(Token::TYPE_VARIABLE,'$foo',1,5))->append(new Token(Token::TYPE_TAG_END,'}}',2,1))->build()],
            [" {{ \n\$foo\n}}",TokenBuilder::begin()->append(new Token(Token::TYPE_TEXT,' ',1 , 1))->append(new Token(Token::TYPE_TAG_START,'{{',1,2))->append(new Token(Token::TYPE_VARIABLE,'$foo',2,1))->append(new Token(Token::TYPE_TAG_END,'}}',3,1))->build()],
//            [' {{ ($a+1)*2 }} ',TokenBuilder::begin()->append(new Token(Token::TYPE_TAG_START,'{{',1))
//                 ->append(new Token(Token::TYPE_PUNCTUATION,'(',1))
//                    ->append(new Token(Token::TYPE_VARIABLE,'$a',1))->append(new Token(Token::TYPE_OPERATOR,'+',1))->append(new Token(Token::TYPE_NUMBER,'1',1))
//                ->append(new Token(Token::TYPE_PUNCTUATION,')',1))
//                ->append(new Token(Token::TYPE_OPERATOR,'*',1))
//                ->append(new Token(Token::TYPE_NUMBER,'2',1))
//                ->append(new Token(Token::TYPE_TAG_END,'}}',1))->build()],
        );
    }

    /**
     * @dataProvider lexProvider
     * @param $tplCode
     * @param $expected
     */
    public function testLex($tplCode,$expected)
    {
        $lexer=new Lexer();
        $tokenStream=$lexer->lex($tplCode);
        $this->assertEquals($tokenStream, $expected);
    }
}