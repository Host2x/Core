<?php

namespace Host2x\Core\Repository;

use XF\Mvc\Entity\Repository;

class UserPackage extends Repository
{
    public function findPackagesForUser(int $userId = null)
    {
        if ($userId === null) {
            $userId = \XF::visitor()->user_id;
        }
        $userId = intval($userId);

        $finder = $this->finder('Host2x\Core:UserPackage')
            ->where('user_id', '=', $userId);

        return $finder;
    }
}