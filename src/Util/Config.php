<?php
/**
 * User: bigbigant
 * Date: Mar 11 2015
 */
namespace Comos\Tage\Util;

class Config
{

    /**
     *
     * @var array
     */
    protected $data;

    /**
     * @param array|\ArrayAccess $data
     * @return Config
     * @throws \InvalidArgumentException
     */
    public static function fromArray($data)
    {
        if (! is_array($data) && ! $data instanceof \ArrayAccess) {
            throw new \InvalidArgumentException('the argument must be array or ArrayAccess');
        }
        return new self($data);
    }

    /**
     *
     * @param array $data            
     */
    protected function __construct($data)
    {
        $this->data = $data;
    }

    /**
     *
     * @param mix $key            
     * @param string $default            
     * @return string|null
     */
    public function str($key, $default = null)
    {
        if (! \key_exists($key, $this->data)) {
            return $default;
        }
        
        return strval($this->data[$key]);
    }
    /**
     * get string field value in restrict mode.
     * @param mix $key
     * @throws ConfigException
     * @return string
     */
    public function rstr($key)
    {
        $value = $this->str($key);
    	if (is_null($value)) {
    		throw new ConfigException('miss required field: '.$key);
    	}
    	return $value;
    }
}