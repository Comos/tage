<?php
/**
 * @author bigbigant
 */
namespace Comos\Tage;

class TestUtil
{

    public static function clearDirectory($pathToDirectory)
    {
        $realpath = \realpath($pathToDirectory);
        if (!$realpath) {
            throw new \Exception('invalid path:'.$pathToDirectory);
        }
        $di = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($realpath));
        $dirs = [];
        $files = [];
        foreach ($di as $file) {
            if (\strlen($file->getRealPath()) <=\strlen($realpath)) {
                continue;
            }
            
            if (\strpos($file->getRealPath(), 'README.md')) {
                continue;
            }
            
            if ($file->isDir()) {
                $dirs[] = $file->getRealPath();
            } else {
                $files[] = $file->getRealPath();
            }
        }
        foreach (array_merge($files, $dirs) as $f) {
            unlink($f);
        }
    }
}
