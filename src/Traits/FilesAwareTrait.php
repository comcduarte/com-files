<?php
namespace Files\Traits;

use Laminas\Db\Adapter\Adapter;
use Files\Model\FilesModel;

trait FilesAwareTrait
{
    /**
     * 
     * @var \Laminas\Db\Adapter\Adapter
     */
    protected $files_adapter = null;
    
    /**
     * 
     * @var FilesModel
     */
    protected $files = null;
    
    /**
     * 
     * @param Adapter $adapter
     * @return self Provides a fluent interface
     */
    public function setFilesAdapter(Adapter $adapter)
    {
        $this->files_adapter = $adapter;
        return $this;
    }
    
    /**
     * 
     * @return Adapter
     */
    public function getFilesAdapter()
    {
        return $this->files_adapter;
    }
    
    /**
     * 
     * @return \Files\Model\FilesModel
     */
    public function getFiles()
    {
        return $this->files;
    }
    
    /**
     * 
     * @param \Files\Model\FilesModel $files
     * @return \Files\Traits\FilesAwareTrait
     */
    public function setFiles($files)
    {
        $this->files = $files;
        return $this;
    }
}