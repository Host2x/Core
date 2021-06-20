<?php

namespace Host2x\Core\Repository;

use XF\Mvc\Entity\Repository;

class Invoice extends Repository
{
    public function getUnpaidInvoices(int $userId = null)
    {
        if ($userId === null) {
            $userId = \XF::visitor()->user_id;
        }
        $userId = intval($userId);

        $finder = $this->finder('Host2x\Core:Invoice')
            ->where('user_id', $userId)
            ->where('is_paid', 0);

        return $finder->fetch();
    }

    public function getUnpaidInvoicesCount(int $userId = null)
    {
        if ($userId === null) {
            $userId = \XF::visitor()->user_id;
        }
        $userId = intval($userId);

        $finder = $this->finder('Host2x\Core:Invoice')
            ->where('user_id', $userId)
            ->where('is_paid', 0);

        return $finder->total();
    }

}