<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午6:09
 */
namespace Tage;

class Tage
{
	/**
	 * @var \Tage\Runtime\TplPreparer
	 */
	private $tplPreparer;
	
    public function __construct($options=array()) {
    	//new \Tage\Runtime\FsBasedTplPreparer();
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