<?php
namespace Comos\Tage\Tests\Runtime;

use Comos\Tage\Runtime\FsBasedTplPreparer;
use Comos\Tage\Tests\TestUtil;

class FsBasedTplPreparerTest extends \PHPUnit_Framework_TestCase
{

    protected $tplDir;

    protected $compiledTplDir;

    protected $greetingTpl;

    protected function setUp()
    {
        parent::setUp();
        
        $this->tplDir = __DIR__ . '/_tpl';
        $this->compiledTplDir = __DIR__ . '/_compiledTpl';
        $this->greetingTpl = $this->tplDir . '/greeting.phtml';
    }
    
    protected function tearDown()
    {
        /**
         * @var \SplFileInfo[]
         */
        TestUtil::clearDirectory($this->compiledTplDir);
        parent::tearDown();
    }

    public function test__construct()
    {
        $options = [
            'tplDir' => __DIR__,
            'compiledTplDir' => __DIR__
        ];
        
        new FsBasedTplPreparer($options, $this->mockOfCompiler());
    }

    /**
     * @dataProvider __construct_InvalidOptionsProvider
     * @expectedException Comos\Tage\Util\ConfigException
     */
    public function test__construct_InvalidOptionFields($options)
    {
        new FsBasedTplPreparer($options, $this->mockOfCompiler());
    }

    public function __construct_InvalidOptionsProvider()
    {
        return [
            [
                [
                    'tplDir' => __DIR__
                ]
            ],
            [
                [
                    'compiledTplDir' => __DIR__
                ]
            ],
            [
                []
            ]
        ];
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test__construct_InvalidOptions()
    {
        new FsBasedTplPreparer(null, $this->mockOfCompiler());
    }
    
    public function testPrepare()
    {
        $filename = 'greeting.phtml';
        $sourceFile = $this->tplDir.'/'.$filename;
        $compiled = $this->tplDir.'/greeting.phtml.compiled.php';
        $compiler = $this->mockOfCompiler();
        $compiler->expects($this->exactly(1))
            ->method('compile')
            ->with($this->equalTo($filename), $this->equalTo(file_get_contents($sourceFile)))
            ->willReturn(file_get_contents($compiled));
        
        $options = [
            'tplDir' => $this->tplDir,
            'compiledTplDir' => $this->compiledTplDir,
        ];
        
        $preparer = new FsBasedTplPreparer($options, $compiler);
        $view = $preparer->prepare($filename);
        $this->assertInstanceOf('Comos\Tage\Runtime\AbstractTemplate', $view);
        
        $view1 = $preparer->prepare($filename);
        $this->assertInstanceOf('Comos\Tage\Runtime\AbstractTemplate', $view1);
    }

    /**
     *
     * @return \Comos\Tage\Compiler\Compiler
     */
    protected function mockOfCompiler()
    {
        return $this->getMockBuilder('\\Comos\\Tage\\Compiler\\Compiler')
            ->setMethods([
            'compile'
        ])
            ->disableOriginalConstructor()
            ->getMock();
    }
}
