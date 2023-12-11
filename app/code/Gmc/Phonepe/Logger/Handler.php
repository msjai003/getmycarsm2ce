<?php

namespace Gmc\Phonepe\Logger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    protected $fileName = '/var/log/phonepe.log';
    protected $loggerType = \Monolog\Logger::DEBUG;
}
