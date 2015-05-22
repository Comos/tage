<?php
namespace Comos\Tage\Compiler\Parser;
use Comos\Tage\Compiler\Node\AbstractNode;

/**
 * User: wangfeng
 * Date: 15-5-17
 * Time: 下午2:05
 */
class CommonTagNode extends AbstractNode
{
    private $tagParser;

    public function __construct($tagParser)
    {
        parent::__construct(['tag'=>$tagParser->getTagToken()], $tagParser->getAttributes());
        $this->tagParser=$tagParser;
    }

    public function compile()
    {
        return $this->tagParser->compile();
    }


}