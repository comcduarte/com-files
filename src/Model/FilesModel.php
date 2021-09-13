<?php
namespace Files\Model;

use Components\Model\AbstractBaseModel;
use Laminas\Db\Sql\Insert;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Where;
use Laminas\Filter\Compress;
use Laminas\Filter\Decompress;
use Laminas\Filter\Decrypt;
use Laminas\Filter\Encrypt;
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
        $date = new \DateTime('now',new \DateTimeZone('UTC'));
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
            $this->encrypt($this->BLOB),
        ]);
        
        $statement = $sql->prepareStatementForSqlObject($insert);
        
        try {
            $statement->execute();
        } catch (Exception $e) {
            return FALSE;
        }
        return TRUE;
    }

    public function read(Array $criteria)
    {
        /****************************************
         * INDEX TABLE
         ****************************************/
        $sql = new Sql($this->adapter);
        
        $select = new Select();
        $select->from($this->getTableName());
        $select->where($criteria);
        
        $statement = $sql->prepareStatementForSqlObject($select);
        
        try {
            $resultSet = $statement->execute();
        } catch (Exception $e) {
            return FALSE;
        }
        
        if ($resultSet->getAffectedRows() == 0) {
            return FALSE;
        } else {
            $this->exchangeArray($resultSet->current());
//             return TRUE;
        }
        
        /****************************************
         * BLOB TABLE
         ****************************************/
        $this->public_attributes = ['UUID','BLOB'];
        
        $sql = new Sql($this->adapter);
        
        $select = new Select();
        $select->from($this->getCurrentDbTableName());
        $select->where($criteria);
        
        $statement = $sql->prepareStatementForSqlObject($select);
        
        try {
            $resultSet = $statement->execute();
        } catch (Exception $e) {
            return FALSE;
        }
        
        if ($resultSet->getAffectedRows() == 0) {
            return FALSE;
        } else {
            $this->exchangeArray($resultSet->current());
//             return TRUE;
        }
        
        $blob = $this->decrypt($this->BLOB);
        $this->BLOB = $blob;
        
        return TRUE;
    }
    
    public function findFiles($reference)
    {
        $where = new Where();
        $where->equalTo('REFERENCE', $reference);
        
        $files = $this->fetchAll($where, ['NAME']);
        return $files;
    }

    /**
     * @param string $value Content to encrypt
     * @return string The encrypted content
     */
    private function encrypt(string $value)
    {
        $compress = new Compress(['adapter' => 'Bz2']);
        
        $encrypt = new Encrypt(['adapter' => 'BlockCipher']);
        $encrypt->setKey('--super-secret-passphrase--');
        
        return $encrypt->filter($compress->filter($value));
    }
    
    /**
     * @param string $value Content to encrypt
     * @return string The encrypted content
     */
    private function decrypt(string $value)
    {
        $decompress = new Decompress(['adapter' => 'Bz2']);
        
        $decrypt = new Decrypt(['adapter' => 'BlockCipher']);
        $decrypt->setKey('--super-secret-passphrase--');
        return $decompress->filter($decrypt->filter($value));
    }
}