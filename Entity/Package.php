<?php

namespace Host2x\Core\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $package_id
 * @property string $type
 * @property string $name
 * @property string $description
 * @property int $required_posts
 * @property int $monthly_posts
 * @property float $price
 * @property int $order
 * @property bool $enabled
 *
 * RELATIONS
 * @property \XF\Mvc\Entity\AbstractCollection|Server[] Servers
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
            'type' => ['type' => self::STR, 'required' => true],
            'name' => ['type' => self::STR, 'required' => true],
            'description' => ['type' => self::STR, 'required' => true],
            'required_posts' => ['type' => self::UINT, 'default' => 0],
            'monthly_posts' => ['type' => self::UINT, 'default' => 0],
            'price' => ['type' => self::FLOAT, 'default' => 0.00],
            'order' => ['type' => self::UINT, 'default' => 0],
            'enabled' => ['type' => self::BOOL, 'default' => false]
        ];

        $structure->getters = [];

        $structure->relations = [
            'UserPackages' => [
                'entity' => 'Host2x\Core:UserPackage',
                'type' => self::TO_MANY,
                'conditions' => [
                    ['package_id', '=', '$package_id']
                ]
            ],
            'Servers' => [
                'entity' => 'Host2x\Core:ServerPackage',
                'type' => self::TO_MANY,
                'conditions' => [
                    ['package_id', '=', '$package_id']
                ]
            ],
        ];

        return $structure;
    }
}