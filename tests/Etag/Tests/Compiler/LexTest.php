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
        return [
            [
                '<html></html>',
                [
                    [
                        // type, value, line, col
                        'TEXT',
                        '<html></html>',
                        1,
                        1
                    ]
                ]
            ],
            [
                ' {{$foo}}',
                [
                    [
                        'TEXT',
                        ' ',
                        1,
                        1
                    ],
                    [
                        'TAG_START',
                        '{{',
                        1,
                        2
                    ],
                    [
                        'VARIABLE',
                        '$foo',
                        1,
                        4
                    ],
                    [
                        'TAG_END',
                        '}}',
                        1,
                        8
                    ]
                ]
            ],
            [
                ' {{ $foo       }}',
                [
                    [
                        'TEXT',
                        ' ',
                        1,
                        1
                    ],
                    [
                        'TAG_START',
                        '{{',
                        1,
                        2
                    ],
                    [
                        'VARIABLE',
                        '$foo',
                        1,
                        5
                    ],
                    [
                        'TAG_END',
                        '}}',
                        1,
                        16
                    ]
                ]
            ],
            [
                " {{ \$foo\n}}",
                [
                    [
                        'TEXT',
                        ' ',
                        1,
                        1
                    ],
                    [
                        'TAG_START',
                        '{{',
                        1,
                        2
                    ],
                    [
                        'VARIABLE',
                        '$foo',
                        1,
                        5
                    ],
                    [
                        'TAG_END',
                        '}}',
                        2,
                        1
                    ]
                ]
            ],
            [
                " {{ \$foo\r\n}}",
                [
                    [
                        'TEXT',
                        ' ',
                        1,
                        1
                    ],
                    [
                        'TAG_START',
                        '{{',
                        1,
                        2
                    ],
                    [
                        'VARIABLE',
                        '$foo',
                        1,
                        5
                    ],
                    [
                        'TAG_END',
                        '}}',
                        2,
                        1
                    ]
                ]
            ],
            [
                " {{ \n\$foo\n}}",
                [
                    [
                        'TEXT',
                        ' ',
                        1,
                        1
                    ],
                    [
                        'TAG_START',
                        '{{',
                        1,
                        2
                    ],
                    [
                        'VARIABLE',
                        '$foo',
                        2,
                        1
                    ],
                    [
                        'TAG_END',
                        '}}',
                        3,
                        1
                    ]
                ]
            ],
            [
                "<?php //{{\$x}}\necho \'{{\$a}}\';?>",
                [
                    [
                        'PHP_CODE',
                        "<?php //{{\$x}}\necho \'{{\$a}}\';?>",
                        1,
                        1
                    ]
                ]
            ],
            [
                "<?='{{\$a}}'?>",
                [
                    [
                        'PHP_CODE',
                        "<?='{{\$a}}'?>",
                        1,
                        1
                    ]
                ]
            ]
        ];
        // [' {{ ($a+1)*2 }} ',TokenBuilder::begin()->append(new Token(Token::TYPE_TAG_START,'{{',1))
        // ->append(new Token(Token::TYPE_PUNCTUATION,'(',1))
        // ->append(new Token(Token::TYPE_VARIABLE,'$a',1))->append(new Token(Token::TYPE_OPERATOR,'+',1))->append(new Token(Token::TYPE_NUMBER,'1',1))
        // ->append(new Token(Token::TYPE_PUNCTUATION,')',1))
        // ->append(new Token(Token::TYPE_OPERATOR,'*',1))
        // ->append(new Token(Token::TYPE_NUMBER,'2',1))
        // ->append(new Token(Token::TYPE_TAG_END,'}}',1))->build()],
    }

    /**
     * @dataProvider lexProvider
     *
     * @param string $tplCode            
     * @param array $expected            
     */
    public function testLex($tplCode, $expected)
    {
        $lexer = new Lexer();
        $tokenStream = $lexer->lex($tplCode);
        $results = [];
        while (! $tokenStream->isEOF()) {
            $current = $tokenStream->next();
            $results[] = [
                substr($current->getTypename(), 5),
                $current->value,
                $current->line,
                $current->col
            ];
        }
        $this->assertEquals($expected, $results);
    }
}