<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午8:42
 */
namespace Comos\Tage\Compiler\Parser\Tag;

use Comos\Tage\Compiler\Parser\CommonTagParser;

class BreakTagParser extends CommonTagParser
{
    public function getTagName()
    {
        return 'break';
    }

    public function compile()
    {
        //TODO check into foreach
        return "\nbreak;";
    }
}