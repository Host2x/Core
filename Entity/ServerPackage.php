<?php

namespace Host2x\Core\Entity;

use XF\Entity\AbstractFieldMap;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $server_id
 * @property int $package_id
 *
 * RELATIONS
 * @property Server Server
 * @property Package Package
 */
class ServerPackage extends AbstractFieldMap
{
    public static function getContainerKey()
    {
        return 'server_id';
    }

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'host2x_server_subdomains';
        $structure->shortName = 'Host2x\Core:ServerPackage';
        $structure->primaryKey = ['server_id', 'package_id'];

        $structure->columns = [
            'server_id' => ['type' => self::UINT, 'nullable' => false],
            'package_id' => ['type' => self::UINT, 'nullable' => false]
        ];

        $structure->getters = [];

        $structure->relations = [
            'Server' => [
                'entity' => 'Host2x\Core:Server',
                'type' => self::TO_ONE,
                'conditions' => 'server_id',
                'primary' => true
            ],

            'Package' => [
                'entity' => 'Host2x\Core:Package',
                'type' => self::TO_ONE,
                'conditions' => 'package_id',
                'primary' => true
            ],
        ];

        return $structure;
    }

}