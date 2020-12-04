<?php
namespace Files\Controller;

use Components\Controller\AbstractConfigController;
use Files\Sql\Ddl\Column\LongBlob;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Ddl\CreateTable;
use Laminas\Db\Sql\Ddl\DropTable;
use Laminas\Db\Sql\Ddl\Column\Datetime;
use Laminas\Db\Sql\Ddl\Column\Integer;
use Laminas\Db\Sql\Ddl\Column\Varchar;
use Laminas\Db\Sql\Ddl\Constraint\PrimaryKey;
use Settings\Model\SettingsModel;

class FilesConfigController extends AbstractConfigController
{
    public function clearDatabase()
    {
        $sql = new Sql($this->adapter);
        $ddl = [];
        
        $settings = new SettingsModel($this->adapter);
        $settings->read(['MODULE' => 'FILES','SETTING' => 'CURRENT_DB']);
        
        for ($i = intval($settings->VALUE); $i > 0; $i--) {
            $next = str_pad($i, strlen($settings->VALUE), '0', STR_PAD_LEFT);
            $ddl[] = new DropTable($next);
        }
        
        
        
        $ddl[] = new DropTable('index');
        $ddl[] = new DropTable('settings');
        
        foreach ($ddl as $obj) {
            $this->adapter->query($sql->buildSqlString($obj), $this->adapter::QUERY_MODE_EXECUTE);
        }
    }

    public function createDatabase()
    {
        $sql = new Sql($this->adapter);
        
        /******************************
         * INDEX
         ******************************/
        $ddl = new CreateTable('index');
        $ddl->addColumn(new Varchar('UUID', 36));
        
        $ddl->addColumn(new Varchar('NAME', 255, TRUE));
        $ddl->addColumn(new Varchar('TYPE', 255, TRUE));
        $ddl->addColumn(new Integer('SIZE', TRUE));
        $ddl->addColumn(new Varchar('ACL', 255, TRUE));
        $ddl->addColumn(new Varchar('REFERENCE', 36, TRUE));
        
        $ddl->addColumn(new Integer('STATUS', TRUE));
        $ddl->addColumn(new Datetime('DATE_CREATED', TRUE));
        $ddl->addColumn(new Datetime('DATE_MODIFIED', TRUE));
        
        $ddl->addConstraint(new PrimaryKey('UUID'));
        
        $this->adapter->query($sql->buildSqlString($ddl), $this->adapter::QUERY_MODE_EXECUTE);
        
        $this->rolloverDatabase();
    }
    
    public function createSettings($module) 
    {
        parent::createSettings($module);
        
        $setting = new SettingsModel($this->adapter);
        $setting->MODULE = $module;
        
        $settings = [
            'CURRENT_DB' => '0000',
        ];
        
        foreach ($settings as $rec => $value) {
            $setting->UUID = $setting->generate_uuid();
            $setting->SETTING = $rec;
            $setting->VALUE = $value;
            $setting->create();
        }
    }
    
    public function rolloverDatabase()
    {
        $this->createSettings('FILES');
        $settings = new SettingsModel($this->adapter);
        $settings->read(['MODULE' => 'FILES','SETTING' => 'CURRENT_DB']);
        $next = str_pad(intval($settings->VALUE) + 1, strlen($settings->VALUE), '0', STR_PAD_LEFT);
        
        $sql = new Sql($this->adapter);
        
        $ddl = new CreateTable($next);
        
        $ddl->addColumn(new Varchar('UUID', 36));
        $ddl->addColumn(new LongBlob('BLOB', NULL, TRUE));
        
        $ddl->addConstraint(new PrimaryKey('UUID'));
        
        $this->adapter->query($sql->buildSqlString($ddl), $this->adapter::QUERY_MODE_EXECUTE);
        
        $settings->VALUE = $next;
        $settings->update();
    }

    
}