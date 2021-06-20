<?php

namespace Host2x\Core\Repository;

use XF\Mvc\Entity\Repository;

class Subdomain extends Repository
{

    public function getAllSubdomains($with = null)
    {
        $finder = $this->finder('Host2x\Core:Subdomain');

        if ($with) {
            $finder->with($with);
        }

        return $finder->fetch();
    }

    public function getEnabledSubdomains($with = null)
    {
        $finder = $this->finder('Host2x\Core:Subdomain')
            ->where('enabled', 1);

        if ($with) {
            $finder->with($with);
        }

        return $finder->fetch();
    }

}