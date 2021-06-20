<?php

namespace Host2x\Core\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $invoice_id
 * @property int $user_id
 * @property float $amount
 * @property int $created
 * @property int $due
 * @property bool $is_paid
 *
 * RELATIONS
 * @property \XF\Entity\User User
 */
class Invoice extends Entity
{
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'host2x_invoices';
        $structure->shortName = 'Host2x\Core:Invoice';
        $structure->primaryKey = 'package_id';

        $structure->columns = [
            'invoice_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'user_id' => ['type' => self::UINT, 'required' => true],
            'amount' => ['type' => self::FLOAT, 'required' => true],
            'created' => ['type' => self::UINT, 'default' => \XF::$time],
            'due' => ['type' => self::UINT, 'default' => date('d/m/Y', strtotime("+15 days"))],
            'is_paid' => ['type' => self::BOOL, 'default' => false]
        ];

        $structure->getters = [];

        $structure->relations = [
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
                'primary' => true
            ],
        ];

        return $structure;
    }
}