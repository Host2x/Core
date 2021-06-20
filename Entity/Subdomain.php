<?php

namespace Host2x\Core\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $subdomain_id
 * @property string $subdomain
 * @property bool $enabled
 *
 * RELATIONS
 * @property \XF\Mvc\Entity\AbstractCollection|Server[] Servers
 */
class Subdomain extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'host2x_subdomains';
        $structure->shortName = 'Host2x\Core:Subdomain';
        $structure->primaryKey = 'subdomain_id';

        $structure->columns = [
            'subdomain_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'subdomain' => ['type' => self::STR, 'required' => true, 'maxLength' => 50],
            'enabled' => ['type' => self::BOOL, 'default' => false],
        ];

        $structure->getters = [];

        $structure->relations = [
            'Servers' => [
                'entity' => 'Host2x\Core:ServerSubdomain',
                'type' => self::TO_MANY,
                'conditions' => [
                    ['subdomain_id', '=', '$subdomain_id']
                ]
            ],
        ];

        return $structure;
    }
}