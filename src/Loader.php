<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2016/7/7
 * Time: 11:59
 */

namespace ClassLoader;


use Symfony\Component\ClassLoader\Psr4ClassLoader;

/**
 * Class Loader
 * Auto require class file recursively in directory.
 * Based on Symfony Psr4ClassLoader.
 * @package ClassLoader
 */
class Loader
{
    /**
     * @var array
     */
    private $register_path = [];

    /**
     * @var string
     */
    private $root;

    /**
     * Loader constructor.
     * @param string $root parent path
     * @param array $register_path [[prefix, path, <recursive>], ...]
     */
    public function __construct($root, array $register_path = [])
    {
        $this->root = $root;
        $this->register_path = $register_path;
    }

    /**
     * @return Psr4ClassLoader
     */
    public function autoload()
    {
        return $this->loadClass($this->register_path);
    }

    /**
     * @param $path
     * @param $prefix
     * @param bool $recursive
     * @return $this
     */
    public function addClassPath($path, $prefix, $recursive = false)
    {
        array_push($this->register_path, [$prefix, $path, $recursive]);
        return $this;
    }

    /**
     * @param array $dirs [[prefix, path, <recursive>], ...]
     * @return Psr4ClassLoader
     */
    public function loadClass(array $dirs)
    {
        $loader = new Psr4ClassLoader();
        $root = realpath($this->root);
        $paths = [];
        foreach ($dirs as $d) {
            if (is_array($d) && count($d) > 1) {
                $prefix = $d[0];
                $path = $d[1];
                $r = false;
                if (count($d) > 2 && $d[2]) {
                    $r = true;
                }
                $p = $this->getClassPath($root, $prefix, $path, $r);
                $paths = array_merge($paths, $p);
            }
        }
        foreach ($paths as $p) {
            $loader->addPrefix($p['prefix'], $p['path']);
        }
        $loader->register();
        return $loader;
    }

    /**
     * @param string $root
     * @param string $prefix
     * @param string $dir
     * @param bool $recursive
     * @return array
     */
    private function getClassPath($root, $prefix, $dir, $recursive = true)
    {
        $paths = [];
        if ($recursive) {
            $p = $this->getPrefixPath($prefix, $root . DIRECTORY_SEPARATOR . $dir);
            $paths = array_merge($paths, $p);
        } else {
            array_push($paths, ['prefix'=>$prefix, 'path'=>$dir]);
        }
        return $paths;
    }

    /**
     * Get prefix recursively in path(find deep into directories if first letter is upper case).
     *
     * @param string $prefix
     * @param string $realPath
     * @return array
     */
    private function getPrefixPath($prefix, $realPath)
    {
        $pp = [];
        array_push($pp, ['prefix'=>$prefix, 'path'=>$realPath]);
        $di = new \DirectoryIterator($realPath);
        foreach($di as $f) {
            if ($f->isDot()) continue;
            if ($f->isFile()) continue;
            if (strpos(basename($f), '.') === 0) continue;
            if ($f->isDir()) {
                //load if first letter is upper case
                $fc = substr($f->getBasename(), 0, 1);
                if ($fc > 64 && $fc < 91) {
                    $pp = array_merge($pp, $this->getPrefixPath($prefix . '\\' . $f->getBasename(), $realPath . DIRECTORY_SEPARATOR . $f->getBasename()));
                }
            }
        }
        return $pp;
    }
}