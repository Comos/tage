<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午8:42
 */
namespace Tage\Compiler\Parser\Tag;

use Tage\Compiler\Parser\TagParser;

class IfTagParser extends TagParser
{
    public function getTagName()
    {
        return 'If';
    }
}