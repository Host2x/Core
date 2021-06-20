<?php

namespace Host2x\Core\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $server_id
 * @property int $subdomain_id
 *
 * RELATIONS
 * @property Server Server
 * @property Subdomain Subdomain
 */
class ServerSubdomain extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'host2x_server_subdomains';
        $structure->shortName = 'Host2x\Core:ServerSubdomain';
        $structure->primaryKey = ['subdomain_id', 'server_id'];

        $structure->columns = [
            'server_id' => ['type' => self::UINT, 'nullable' => false],
            'subdomain_id' => ['type' => self::UINT, 'nullable' => false]
        ];

        $structure->getters = [];

        $structure->relations = [
            'Server' => [
                'entity' => 'Host2x\Core:Server',
                'type' => self::TO_ONE,
                'conditions' => 'server_id',
                'primary' => true
            ],

            'Subdomain' => [
                'entity' => 'Host2x\Core:Subdomain',
                'type' => self::TO_ONE,
                'conditions' => 'subdomain_id',
                'primary' => true
            ],
        ];

        return $structure;
    }

}