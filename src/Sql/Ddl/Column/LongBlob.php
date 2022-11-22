<?php
namespace Files\Sql\Ddl\Column;

use Laminas\Db\Sql\Ddl\Column\AbstractLengthColumn;

class LongBlob extends AbstractLengthColumn
{
    /**
     * @var string Change type to blob
     */
    protected $type = 'LONGBLOB';
}