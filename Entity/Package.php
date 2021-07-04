<?php

namespace Host2x\Core\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $user_package_id
 * @property int $server_id
 * @property int $package_id
 * @property string $username
 * @property string $domain
 * @property string $status
 * @property string $billing_type
 *
 * RELATIONS
 * @property \XF\Entity\User User
 * @property Plan Package
 */
class Package extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'host2x_packages';
        $structure->shortName = 'Host2x\Core:Package';
        $structure->primaryKey = 'package_id';

        $structure->columns = [
            'package_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'user_id' => ['type' => self::UINT, 'nullable' => false],
            'plan_id' => ['type' => self::UINT, 'nullable' => false],
            'username' => ['type' => self::STR, 'unique' => true, 'required' => true, 'maxLength' => 50],
            'domain' => ['type' => self::STR, 'required' => true],
            'status' => ['type' => self::STR, 'required' => true],
            'period' => ['type' => self::STR, 'required' => true],
        ];

        $structure->getters = [];

        $structure->relations = [
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
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