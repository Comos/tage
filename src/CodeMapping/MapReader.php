<?php
/**
 * Created by PhpStorm.
 * User: zhaoqing
 * Date: 15/5/6
 * Time: 下午4:42
 */

namespace Comos\Tage\CodeMapping;

class MapReader
{
    private $sourceLines;
    private $targetLines;

    /**
     * @param string $serializedData
     * @return MapReader
     * @throws \InvalidArgumentException
     */
    public static function fromSerializedData($serializedData) {
        $data = unserialize($serializedData);
        return new MapReader($data);
    }

    /**
     * @param array $data
     * @throws \InvalidArgumentException
     */
    public function __construct($data)
    {
        if (!is_array($data) || !isset($data['s']) || !isset($data['t'])) {
            throw new \InvalidArgumentException('invalid mapping data');
        }
        $this->sourceLines = $data['s'];
        $this->targetLines = $data['t'];
    }

    /**
     * @param $targetLine
     * @return int
     */
    public function getSourceLine($targetLine)
    {
        $sectionIndex = self::getSectionIndex($this->targetLines, $targetLine);
        $delta = $targetLine - $this->targetLines[$sectionIndex];
        $sourceSectionLine = $this->sourceLines[$sectionIndex];
        if (!$delta) {
            return $sourceSectionLine;
        }

        $hasNextSection = count($this->sourceLines) - 1 != $sectionIndex;
        if (!$hasNextSection) {
            return $sourceSectionLine + $delta;
        }
        return min($sourceSectionLine + $delta, $this->sourceLines[$sectionIndex + 1]);
    }

    /**
     * @param array $list
     * @param int $line
     * @param int $from
     * @param int $to
     * @return int
     */
    public static function getSectionIndex($list, $line, $from = 0, $to = null)
    {
        if (is_null($to)) {
            $to = count($list) - 1;
        }
        if (($to - $from) <= 1) {
            if ($line >= $list[$to]) {
                return $to;
            }
            return $from;
        }

        $middle = $from + floor($to - $from) / 2;
        $value = $list[$middle];
        if ($value == $line) {
            return $middle;
        }
        if ($value > $line) {
            return self::getSectionIndex($list, $line, $from, $middle);
        }
        return self::getSectionIndex($list, $line, $middle, $to);
    }
}