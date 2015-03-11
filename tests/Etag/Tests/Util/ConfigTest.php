<?php
/**
 * User: bigbigant
 * Date: Mar 11 2015
 */
namespace Etag\Tests\Util;

use Etag\Util\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    
    public function testFromArray() {
        $this->assertTrue(Config::fromArray(['a'=>1]) instanceof Config);
        $this->assertTrue(Config::fromArray(new \ArrayObject(['a'=>1])) instanceof Config);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testFromArray_InvalidArgument() {
    	Config::fromArray('x');
    }
    
    /**
     * @dataProvider getParamMethodsProvider
     */
    public function testGetParamMethods($method, $data, $key, $default, $expectedValue) {
        $result = Config::fromArray($data)->$method($key, $default);
        $this->assertEquals($expectedValue, $result);

        if (is_null($default)) {
        	$result1 = Config::fromArray($data)->$method($key);
        	$this->assertEquals($expectedValue, $result);
        }
    }
    
    public function getParamMethodsProvider() {
        return [
        	//$method, $data, $key, $default, $expectedValue
        	['str', ['a'=>'1', 'b' => 2], 'a', null, '1'],
        	['str', ['a'=>'1', 'b' => 2], 'b', null, '2'],
        	['str', ['a'=>'1', 'b' => 2], 'c', null, null],
        	['str', ['a'=>'1', 'b' => 2], 'c', 'x', 'x'],
        	['str', ['a','b','c'], 2, null, 'c'],
        	['str', ['a','b','c'], '2', null, 'c'],
        ];
    }
    
    
    /**
     * @dataProvider getParamMethodsProvider_InRestrictMode_MissRequiredField
     * @expectedException Etag\Util\ConfigException
     */
    public function testGetParamMethods_InRestrictMode_MissRequiredField($method, $data, $key) {
    	Config::fromArray($data)->$method($key);
    }
    
    public function getParamMethodsProvider_InRestrictMode_MissRequiredField() {
    	return [
    	   //$method, $data, $key
    	   ['rstr', ['a'=>'1', 'b' => 2], 'c'],
    	   ['rstr', ['a'=>'1', 'b' => 2], 'x'],
    	   ['rstr', ['a', 'b', 'c'], 3],
		];
    }
    
}