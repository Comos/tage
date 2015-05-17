<?php
namespace Comos\Tage\Tests\Runtime;

use Comos\Tage\Runtime\FsBasedTpl;

class FsBasedTplTest extends \PHPUnit_Framework_TestCase
{
    protected $targetFile;

    protected $sourceFile;
    
    protected function setUp()
    {
        $this->targetFile = __FILE__.'.target.tmp';
        $this->sourceFile = __FILE__.'.source.tmp';
    }
    /**
     * @return \Tage\Runtime\FsBasedTplTest
     */
    protected function getTplInst()
    {
        return new FsBasedTpl($this->sourceFile, $this->targetFile);
    }
    
    protected function tearDown()
    {
        @unlink($this->targetFile);
        @unlink($this->sourceFile);
    }

    public function testCheckTarget()
    {
        $mtime = \time();
        \touch($this->sourceFile, $mtime);
        \touch($this->targetFile, $mtime);
        $result = $this->getTplInst()->checkTarget();
        $this->assertTrue($result);
    }
    /**
     * @expectedException Comos\Tage\Runtime\Exception
     * @expectedExceptionMessage fail to get mtime from file
     */
    public function testCheckTarget_SourceDoesNotExist()
    {
        $this->getTplInst()->checkTarget();
    }
    
    public function testCheckTarget_TargetDoesNotExist()
    {
        \touch($this->sourceFile);
        $result = $this->getTplInst()->checkTarget();
        $this->assertFalse($result);
    }
    
    public function testCheckTarget_TargetIsOutOfDate()
    {
        \touch($this->sourceFile);
        \touch($this->targetFile, time() - 10);
        $result = $this->getTplInst()->checkTarget();
        $this->assertFalse($result);
    }
    
    public function testCheckTarget_TargetIsTooFresh()
    {
        \touch($this->sourceFile);
        \touch($this->targetFile, time() + 10);
        $result = $this->getTplInst()->checkTarget();
        $this->assertFalse($result);
    }
    
    public function testWriteTarget() {
        \file_put_contents($this->sourceFile, 'bbb');
        $data = 'aaa';
        $this->getTplInst()->writeTarget($data);
        $result = file_get_contents($this->targetFile);
        $this->assertEquals($data, $result);
    }
}
