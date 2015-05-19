<?php
/**
 * Created by PhpStorm.
 * User: zhaoqing
 * Date: 15/5/8
 * Time: 下午2:39
 */

namespace Comos\Tage\CodeMapping;


class MapReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $list
     * @param $line
     * @param $expected
     * @dataProvider providerOfTestGetSectionOffset
     */
    public function testGetSectionOffset($list, $line, $expected)
    {
        $actual = MapReader::getSectionIndex($list, $line);
        $this->assertEquals($expected, $actual);
    }

    public function providerOfTestGetSectionOffset()
    {
        return array(
            array(
                //list,  line,   expected
                array(0, 1, 3, 5, 9), 4, 2
            ),
            array(
                //list,  line,   expected
                array(0, 1, 3, 5, 9), 3, 2
            ),
            array(
                //list,  line,   expected
                array(0, 1, 3, 5, 9), 0, 0
            ),
            array(
                //list,  line,   expected
                array(0, 1, 3, 5, 9), 9, 4
            ),
            array(
                //list,  line,   expected
                array(0), 10, 0
            ),
            array(
                array(0, 3), 4, 1
            ),
        );
    }

    /**
     * @param $data
     * @param $targetLine
     * @param $expectedSourceLine
     * @dataProvider providerOfGetSourceLine
     */
    public function testGetSourceLine($data, $targetLine, $expectedSourceLine)
    {
        $reader = new MapReader($data);
        $sourceLine = $reader->getSourceLine($targetLine);
        $this->assertEquals($expectedSourceLine, $sourceLine, "target line: $targetLine");
    }

    /**
     * @param $data
     * @param $targetLine
     * @param $expectedSourceLine
     * @dataProvider providerOfGetSourceLine
     */
    public function testFromSerializedData($data, $targetLine, $expectedSourceLine)
    {
        $reader = MapReader::fromSerializedData(serialize($data));
        $sourceLine = $reader->getSourceLine($targetLine);
        $this->assertEquals($expectedSourceLine, $sourceLine, "target line: $targetLine");
    }

    public function providerOfGetSourceLine()
    {
        $defaultMap = array(
            's' => array(0, 10, 13, 15),
            't' => array(0, 10, 18, 25),
        );
        return array(
            //map, targetLine, sourceLine
            array($defaultMap, 1, 1,),
            array($defaultMap, 10, 10,),
            array($defaultMap, 11, 11,),
            array($defaultMap, 18, 13,),
            array($defaultMap, 19, 14,),
            array($defaultMap, 24, 15,),
        );
    }

}