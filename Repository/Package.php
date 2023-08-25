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

    public function findByDomain(string $domain) {
        $finder = $this->finder('Host2x\Core:Package')
            ->where('domain', '=', $domain);

        return $finder;
    }

    public function findByUsername(string $username) {
        $finder = $this->finder('Host2x\Core:Package')
            ->where('username', '=', $username);

        return $finder;
    }
}