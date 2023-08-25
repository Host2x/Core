<?php

namespace Host2x\Core\Entity;

use XF\Entity\AbstractFieldMap;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $server_id
 * @property int $plan_id
 *
 * RELATIONS
 * @property Server Server
 * @property Plan Package
 */
class ServerPlan extends AbstractFieldMap
{
    public static function getContainerKey()
    {
        return 'server_id';
    }

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'host2x_server_plans';
        $structure->shortName = 'Host2x\Core:ServerPlan';
        $structure->primaryKey = ['server_id', 'plan_id'];

        $structure->columns = [
            'server_id' => ['type' => self::UINT, 'nullable' => false],
            'plan_id' => ['type' => self::UINT, 'nullable' => false]
        ];

        $structure->getters = [];

        $structure->relations = [
            'Server' => [
                'entity' => 'Host2x\Core:Server',
                'type' => self::TO_ONE,
                'conditions' => 'server_id',
                'primary' => true
            ],

            'Plan' => [
                'entity' => 'Host2x\Core:Plan',
                'type' => self::TO_ONE,
                'conditions' => 'plan_id',
                'primary' => true
            ],
        ];

        return $structure;
    }

}