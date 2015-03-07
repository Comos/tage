<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午5:28
 */
namespace Etag\Tests\Compiler;

use Etag\Compiler\Lexer;
use Etag\Compiler\Token;
use Etag\Tests\EtagTest;
use Etag\Tests\TokenBuilder;

class LexTest extends EtagTest
{
    public function lexProvider()
    {
        return array(
            ['<html></html>',TokenBuilder::begin()->append(new Token(Token::TYPE_TEXT,'<html></html>',1))->build()],
            [' {{$foo}} ',TokenBuilder::begin()->append(new Token(Token::TYPE_TAG_START,'{{',1))->append(new Token(Token::TYPE_VARIABLE,'$foo',1))->append(new Token(Token::TYPE_TAG_END,'}}',1))->build()],
            [' {{ $foo       }} ',TokenBuilder::begin()->append(new Token(Token::TYPE_TAG_START,'{{',1))->append(new Token(Token::TYPE_VARIABLE,'$foo',1))->append(new Token(Token::TYPE_TAG_END,'}}',1))->build()],
            [" {{ \$foo\n}} ",TokenBuilder::begin()->append(new Token(Token::TYPE_TAG_START,'{{',1))->append(new Token(Token::TYPE_VARIABLE,'$foo',1))->append(new Token(Token::TYPE_TAG_END,'}}',2))->build()],
            [" {{ \n\$foo\n}} ",TokenBuilder::begin()->append(new Token(Token::TYPE_TAG_START,'{{',1))->append(new Token(Token::TYPE_VARIABLE,'$foo',2))->append(new Token(Token::TYPE_TAG_END,'}}',3))->build()],
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