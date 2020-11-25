<?php
namespace Files\View\Helper;

use Laminas\View\Helper\AbstractHelper;

class Files extends AbstractHelper
{
    public function __invoke()
    {
        return 'Hello World';
    }
}