<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午6:31
 */
namespace Etag\Tests;

class MilestoneTest extends EtagTestCase
{
    public function testMilestones()
    {
        $testFiles=glob(__DIR__.'/Fixtures/Milestones/*.test');
        foreach($testFiles as $testFile){
            $this->runFixtureTest($testFile);
        }
    }
}