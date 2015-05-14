<?php
/**
 * Created by PhpStorm.
 * User: zhaoqing
 * Date: 15/5/6
 * Time: 下午4:42
 */

namespace Comos\Tage\CodeMapping;


class MapBuilder
{
    public function add($sourceLine, $targetLine)
    {
        $this->sourceLines[] = $sourceLine;
        $this->targetLines[] = $targetLine;
    }

    public function getSerializedData($target)
    {
        return serialize(array('s'=>$this->sourceLines, 't'=>$this->targetLines));
    }
}