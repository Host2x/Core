<?php

namespace Host2x\Core\Repository;

use XF\Mvc\Entity\Repository;

class Package extends Repository
{
    public function findPackagesForUser(int $userId = null)
    {
        if ($userId === null) {
            $userId = \XF::visitor()->user_id;
        }
        $userId = intval($userId);

        $finder = $this->finder('Host2x\Core:Package')
            ->where('user_id', '=', $userId);

        return $finder;
    }
}