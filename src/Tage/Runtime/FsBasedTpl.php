<?php
/**
 * User: bigbigant
 * Date: Mar 11 2015
 */
namespace Tage\Runtime;

class FsBasedTpl
{

    private $sourceFile;

    private $targetFile;

    public function __construct($sourceFile, $targetFile)
    {
        $this->sourceFile = $sourceFile;
        $this->targetFile = $targetFile;
    }

    /**
     * return boolean
     */
    public function checkTarget()
    {
        return false;
    }

    /**
     *
     * @param string $content            
     */
    public function writeTarget($content)
    {}

    /**
     *
     * @return string
     */
    public function loadSource()
    {
        return '';
    }
}