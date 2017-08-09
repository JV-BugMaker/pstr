<?php
/**
 * Created by PhpStorm.
 * User: JV
 * Date: 2017/8/9
 * Time: 16:51
 * Author: JV
 */
namespace PSTR\Components\File\src;
/***
 * Class FileUpload
 * @package PSTR\Components\File\src
 * Author: JV
 */
class FileUpload
{
    /***
     * @var
     * Author: JV
     */
    public static $_instance;
    /**
     * @var
     * Author: JV
     */
    private $publicPath;
    /**
     * @var string
     * Author: JV
     */
    private $fileAttribute = 'file';
    /**
     * @var string
     * Author: JV
     */
    private $mimeAttribute = 'mime_type';
    /***
     * @var string
     * Author: JV
     */
    private $sizeAttribute = 'size';
    /***
     * @var string
     * Author: JV
     */
    private $displayNameAttribute = 'name';
    /***
     * @var string
     * Author: JV
     */
    private $fileNameAttribute = 'fileName';
    /**
     * @var string
     * Author: JV
     */
    private $tempPathAttribute = 'temp';




    private function __construct()
    {

    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    public static function getInstance()
    {
        if(!self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /***
     * @param string $file
     * @throws \Exception
     * Author: JV
     */
    public function init(string $file)
    {
        $files = $_FILES;
        unset($_FILES);
        if($file && !isset($files[$file])){
            throw new \Exception('参数错误');
        }else{
            $file = reset($files);
        }
        $config = app()->config('file');
        $this->publicPath = $config['publicPath'];
        $this->mimeAttribute = $file['type'];
        $this->fileNameAttribute = $file['name'];
        $this->tempPathAttribute = $file['tmp_name'];
        $this->sizeAttribute = $file['size'];
        $this->fileAttribute = $file;
    }

    /***
     * @return string
     * Author: JV
     */
    public function getFileType():string
    {
        return $this->mimeAttribute;
    }

    /***
     * @return string
     * Author: JV
     */
    public function getFileExtendName():string
    {
        $extend_name = substr($this->fileNameAttribute,strpos(',',$this->fileNameAttribute));
        return $extend_name;
    }

    /***
     * @return string
     * Author: JV
     */
    public function getSize():string
    {
        return $this->sizeAttribute;
    }

    /***
     * @return string
     * Author: JV
     */
    public function displayName():string
    {
        return $this->displayNameAttribute;
    }

    /***
     * @return bool
     * @throws \Exception
     * Author: JV
     */
    public function moveToSave():bool
    {
        $rs = move_uploaded_file($this->tempPathAttribute,$this->publicPath.'/'.$this->displayNameAttribute);
        if(!$rs){
            throw new \Exception('文件保存失败');
        }
        return $rs;
    }
}