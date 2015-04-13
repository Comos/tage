<?php
/**
 * User: bigbigant
 * Date: Mar 11 2015
 */
namespace Comos\Tage\Util;

use Comos\Tage\Util\Config;

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
     * @dataProvider getParamMethodsProvider_RestrictMode_MissRequiredField_DataProvider
     * @expectedException Comos\Tage\Util\ConfigException
     */
    public function testGetParamMethods_RestrictMode_MissRequiredField($method, $data, $key) {
    	Config::fromArray($data)->$method($key);
    }
    
    public function getParamMethodsProvider_RestrictMode_MissRequiredField_DataProvider() {
    	return [
    	   //$method, $data, $key
    	   ['rstr', ['a'=>'1', 'b' => 2], 'c'],
    	   ['rstr', ['a'=>'1', 'b' => 2], 'x'],
    	   ['rstr', ['a', 'b', 'c'], 3],
		];
    }
    
    /**
     * @param string $method
     * @param array $data
     * @param string $key
     * @param string $expectedValue
     * @dataProvider getParamMethodsProvider_RestrictMode_DataProvider
     */
    public function testGetParamMethods_RestrictMode($method, $data, $key, $expectedValue) {
        $result = Config::fromArray($data)->$method($key);
        $this->assertEquals($expectedValue, $result);
    }
    
    public function getParamMethodsProvider_RestrictMode_DataProvider() {
        return [
        	   //$method, $data, $key
        	   ['rstr', ['a'=>'1', 'b' => 2], 'b', '2'],
        	   ['rstr', ['a'=>'1', 'b' => 2], 'a', '1'],
        	   ['rstr', ['a', 'b', 'c'], 2, 'c'],
               ['rstr', ['a', 'b', null], 2, ''],
        ];
    }
}