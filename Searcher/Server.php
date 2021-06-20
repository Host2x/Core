<?php

namespace Host2x\Core\Searcher;

use XF\Searcher\AbstractSearcher;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Manager;

class Server extends AbstractSearcher
{

    protected $allowedRelations = ['Package', 'Subdomain'];

    protected $formats = [
        'name' => 'like',
        'hostname' => 'like'
    ];

    protected $arrayValueKeys = [
        'server_field'
    ];

    protected $whitelistOrder = [
        'name' => true,
        'hostname' => true
    ];

    protected $order = [['name', 'asc']];

    protected function getEntityType()
    {
        return 'Host2x\Core:Server';
    }

    protected function getDefaultOrderOptions()
    {
       return [
            'name' => \XF::phrase('name'),
            'hostname' => \XF::phrase('host2x_core_hostname'),
        ];
    }

}