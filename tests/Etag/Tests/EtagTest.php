<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午5:29
 */
namespace Etag\Tests;

use Etag\Autoloader;
use Etag\Compiler\Token;
use Etag\Compiler\TokenStream;
use Etag\Etag;
require_once __DIR__.'/../../../src/Etag/Autoloader.php';

Autoloader::register();
class TokenBuilder
{
    public $tokens;

    public static function begin()
    {
        return new TokenBuilder();
    }

    public function append($token)
    {
        $this->tokens[]=$token;
        return $this;
    }

    public function build()
    {
        $this->tokens[]=new Token(Token::TYPE_EOF,'',-1,-1);
        return new TokenStream($this->tokens,'default');
    }

}

class EtagTest extends  \PHPUnit_Framework_TestCase
{
    public function runFixtureTest($path)
    {
        $directives=['TEST','TEMPLATE','DATA','EXPECT','EXCEPTION'];
        $justDirectives=array_map(function($x){return '--'.$x.'--';},$directives);
        $parseConfig=array();
        $fixtureLines=explode("\n",file_get_contents($path));
        $lastDirective = '';
        foreach($fixtureLines as $line){
            if(in_array($line,$justDirectives)){
                $lastDirective=$line;
            }
            if($lastDirective){
                $parseConfig[$lastDirective].=$line."\n";
            }
        }

        $testDescription=$parseConfig['--TEST--'];
        $template=$parseConfig['--TEMPLATE--'];
        $vars=[];
        if(isset($parseConfig['--DATA--'])){
            $vars=eval($parseConfig['--DATA--']);
        }
        try{
            echo $testDescription;
            $etag = new Etag();
            $actual=$etag->display($template, $vars);
            if(isset($parseConfig['--EXPECT--'])){
                $this->assertEquals($parseConfig['--EXPECT--'],$actual);
            }
        }catch (\Exception $ex){
            if(isset($parseConfig['--EXCEPTION--'])){
                $this->assertEquals($parseConfig['--EXCEPTION--'], get_class($ex).':'.$ex->getMessage());
            }else{
                throw $ex;
            }
        }

    }
}