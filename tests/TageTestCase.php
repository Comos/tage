<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午5:29
 */
namespace Tage\Tests;

use Tage\Compiler\Parser\Parser;
use Tage\Tage;

class TageTestCase extends \PHPUnit_Framework_TestCase
{

    public function runFixtureTest($path)
    {
        $directives = [
            'TEST',
            'TEMPLATE',
            'DATA',
            'EXPECT',
            'EXCEPTION'
        ];
        $justDirectives = array_map(function ($x)
        {
            return '--' . $x . '--';
        }, $directives);
        $parseConfig = array();
        $fixtureLines = explode("\n", file_get_contents($path));
        $lastDirective = '';
        foreach ($fixtureLines as $line) {
            if (in_array(\trim($line), $justDirectives)) {
                $lastDirective = $line;
                $parseConfig[$lastDirective] = '';
                continue;
            }
            if ($lastDirective) {
                $parseConfig[$lastDirective] .= $line . "\n";
            }
        }
        $testDescription = $parseConfig['--TEST--'];
        $template = $parseConfig['--TEMPLATE--'];
        $vars = [];
        if (isset($parseConfig['--DATA--'])) {
            $vars = eval($parseConfig['--DATA--']);
        }
        
        try {
            $tplName = $this->prepareSourceTpl($path, $template);
            
            $tage = new Tage([
                'tplDir' => $this->_tplDir,
                'compiledTplDir' => $this->_compileTplDir
            ]);
            
           \ob_start();
            $tage->display($tplName, $vars);
            $actual =\ob_get_contents();
           \ob_end_clean();
            
            if (isset($parseConfig['--EXPECT--'])) {
                $this->assertEquals($parseConfig['--EXPECT--'], $actual, $testDescription);
            }
        } catch (\Exception $ex) {
            if (isset($parseConfig['--EXCEPTION--'])) {
                $this->assertEquals($parseConfig['--EXCEPTION--'], get_class($ex) . ':' . $ex->getMessage(), $testDescription);
            } else {
                throw $ex;
            }
        }
    }

    protected $_tplDir;

    protected $_compileTplDir;

    protected function setUp()
    {
        parent::setUp();
        $this->_tplDir = __DIR__ . '/_tpls';
        $this->_compileTplDir = __DIR__ . '/_compiledTpls';
    }

    protected function tearDown()
    {
        TestUtil::clearDirectory($this->_tplDir);
        TestUtil::clearDirectory($this->_compileTplDir);
        parent::tearDown();
    }

    /**
     *
     * @param string $path            
     * @param string $content            
     * @return string
     */
    protected function prepareSourceTpl($path, $content)
    {
        $filename = md5($path) . '.tpl';
        $path = $this->_tplDir . '/' . $filename;
        $r = file_put_contents($path, $content);
        if ($r === false) {
            throw new \Exception('fail to write tpl:' . $filename);
        }
        return $filename;
    }
}