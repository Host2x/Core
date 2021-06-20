<?php

namespace Host2x\Core\Repository;

use XF\Mvc\Entity\Repository;

class Package extends Repository
{
    public function getAllPackages($with = null)
    {
        $finder = $this->finder('Host2x\Core:Package')
            ->order('order');

        if ($with)
        {
            $finder->with($with);
        }

        return $finder->fetch();
    }

    public function getEnabledPackages($with = null)
    {
        $finder = $this->finder('Host2x\Core:Package')
            ->where('enabled', '=', true)
            ->order('order');

        if ($with)
        {
            $finder->with($with);
        }

        return $finder->fetch();
    }
}