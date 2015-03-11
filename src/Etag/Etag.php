<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午6:09
 */
namespace Etag;

class Etag
{
	/**
	 * @var \Etag\Runtime\TplPreparer
	 */
	private $tplPreparer;
	
    public function __construct($options=array()) {
    	//new \Etag\Runtime\FsBasedTplPreparer();
    }
    
    /**
     * @param $name
     * @param $vars
     * @return string
     */
    public function display($name,$vars)
    {
        try {
        	
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}