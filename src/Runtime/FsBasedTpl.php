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

    private $sourceMtime;

    public function __construct($sourceFile, $targetFile)
    {
        $this->sourceFile = $sourceFile;
        $this->targetFile = $targetFile;
    }

    /**
     *
     * @throws Exception
     * @return boolean
     */
    public function checkTarget()
    {
        $targetMtime = @\filemtime($this->targetFile);
        if ($targetMtime === $this->getSourceMtime()) {
            return true;
        }
        return false;
    }

    public function getSourceMtime()
    {
        if (! $this->sourceMtime) {
            $this->sourceMtime = @\filemtime($this->sourceFile);
            if (! $this->sourceMtime) {
                throw new Exception('fail to get mtime from file: ' . $this->sourceFile);
            }
        }
        return $this->sourceMtime;
    }

    /**
     *
     * @throws FailToPrepareTplException
     * @param string $content            
     */
    public function writeTarget($content)
    {
        $tmpFile = uniqid($this->targetFile . '.tmp.');
        $result = @\file_put_contents($tmpFile, $content);
        if ($result === false) {
            throw new FailToPrepareTplException('fail to write temp compiled file:' . $tmpFile);
        }
        
        $result = @\rename($tmpFile, $this->targetFile) && @\touch($this->targetFile, $this->getSourceMtime());
        
        if (false === $result) {
            @unlink($tmpFile);
            throw new FailToPrepareTplException('fail to mv temp compiled file to target:' . $tmpFile);
        }
    }

    /**
     *
     * @return string
     * @throws FailToPrepareTplException
     */
    public function loadSource()
    {
        $data = @\file_get_contents($this->sourceFile);
        if ($data === false) {
            throw new FailToPrepareTplException('fail to read file:' . $this->sourceFile);
        }
        return $data;
    }
}