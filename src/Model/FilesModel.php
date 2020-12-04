<?php
namespace Files\Model;

use Components\Model\AbstractBaseModel;
use Laminas\Db\Sql\Insert;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Where;
use Settings\Model\SettingsModel;
use Exception;

class FilesModel extends AbstractBaseModel
{
    const INDEX_TABLE = 'index';
    
    public $NAME;
    public $TYPE;
    public $SIZE;
    public $ACL;
    public $BLOB;
    public $REFERENCE;
    
    public function __construct()
    {
//         $this->setDbAdapter($adapter);
        
        $this->private_attributes = [
            'adapter',                  //-- From AdapterAwareTrait --//
            'table',
            'inputFilter',
            'private_attributes',
            'public_attributes',
            'primary_key',
            'required',
            'current_user',
        ];
        $this->UUID = $this->generate_uuid();
        $this->setPrimaryKey('UUID');
        
        $date = new \DateTime('now',new \DateTimeZone('EDT'));
        $today = $date->format('Y-m-d H:i:s');
        $this->DATE_CREATED = $today;
        
        $this->STATUS = $this::ACTIVE_STATUS;
        
        $this->public_attributes = array_diff(array_keys(get_object_vars($this)), $this->private_attributes);
    }
    
    public function getTableName()
    {

        
        return $this::INDEX_TABLE;
    }
    
    public function getCurrentDbTableName()
    {
        $settings = new SettingsModel($this->adapter);
        $settings->read(['MODULE' => 'FILES','SETTING' => 'CURRENT_DB']);
        return $settings->VALUE;
    }
    
    public function create()
    {
        $date = new \DateTime('now',new \DateTimeZone('EDT'));
        $this->DATE_CREATED = $date->format('Y-m-d H:i:s');
        
        if (is_null($this->UUID)) {
            $this->UUID = $this->generate_uuid();
        }
        
        $sql = new Sql($this->adapter);
        
        /******************************
         * INDEX RECORD
         ******************************/
        $insert = new Insert();
        $insert->into($this::INDEX_TABLE);
        $insert->columns([
            'UUID','NAME','TYPE','SIZE','ACL','REFERENCE','STATUS','DATE_CREATED','DATE_MODIFIED',
        ]);
        $insert->values([
            $this->UUID,
            $this->NAME,
            $this->TYPE,
            $this->SIZE,
            $this->ACL,
            $this->REFERENCE,
            $this->STATUS,
            $this->DATE_CREATED,
            $this->DATE_MODIFIED,
        ]);
        $statement = $sql->prepareStatementForSqlObject($insert);
        
        try {
            $statement->execute();
        } catch (Exception $e) {
            return FALSE;
        }
        
        /******************************
         * BLOB RECORD
         ******************************/
        $insert = new Insert();
        $insert->into($this->getCurrentDbTableName());
        $insert->columns([
            'UUID','BLOB',
        ]);
        
        $insert->values([
            $this->UUID,
            $this->BLOB,
        ]);
        
        $statement = $sql->prepareStatementForSqlObject($insert);
        
        try {
            $statement->execute();
        } catch (Exception $e) {
            return FALSE;
        }
        return TRUE;
    }

    public function findFiles($reference)
    {
        $where = new Where();
        $where->equalTo('REFERENCE', $reference);
        
        $files = $this->fetchAll($where, ['NAME']);
        return $files;
    }


}