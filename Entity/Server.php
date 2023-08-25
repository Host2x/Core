<?php

namespace Host2x\Core\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $server_id
 * @property string $type
 * @property string $name
 * @property string $hostname
 * @property string|null $password
 * @property string|null $apikey
 * @property boolean $is_premium
 *
 * RELATIONS
 * @property \XF\Mvc\Entity\AbstractCollection|Plan[] Plans
 * @property \XF\Mvc\Entity\AbstractCollection|ServerSubdomain[] Subdomains
 */
class Server extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'host2x_servers';
        $structure->shortName = 'Host2x\Core:Server';
        $structure->primaryKey = 'server_id';

        $structure->columns = [
            'server_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'type' => ['type' => self::STR, 'required' => true],
            'name' => ['type' => self::STR, 'required' => true],
            'hostname' => ['type' => self::STR, 'required' => true],
            'port' => ['type' => self::INT, 'required' => true],
            'username' => ['type' => self::STR, 'required' => true],
            'password' => ['type' => self::STR, 'required' => false, 'nullable' => true],
            'apikey' => ['type' => self::STR, 'required' => false, 'nullable' => true],
            'is_premium' => ['type' => self::BOOL, 'required' => false, 'default' => 0],
            'use_ssl' => ['type' => self::BOOL, 'required' => false, 'default' => 0]
        ];

        $structure->getters = [];

        $structure->relations = [
            'Plans' => [
                'entity' => 'Host2x\Core:ServerPlan',
                'type' => self::TO_MANY,
                'conditions' => [
                    ['server_id', '=', '$server_id']
                ]
            ],

            'Subdomains' => [
                'entity' => 'Host2x\Core:ServerSubdomain',
                'type' => self::TO_MANY,
                'conditions' => [
                    ['server_id', '=', '$server_id']
                ]
            ],
        ];

        return $structure;
    }



    /**
     * @return \XF\Mvc\Entity\AbstractCollection
     */
    public function getPlans()
    {
        $pivot = $this->Packages; // or whatever you named your pivot relation
        return $pivot->pluckNamed('Plan', 'plan_id');
    }

}