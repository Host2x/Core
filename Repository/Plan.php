<?php

namespace Host2x\Core\Repository;

use XF\Mvc\Entity\Repository;

class Plan extends Repository
{
    public function getAllPlans($with = null)
    {
        $finder = $this->finder('Host2x\Core:Plan')
            ->order('order');

        if ($with)
        {
            $finder->with($with);
        }

        return $finder->fetch();
    }

    public function getEnabledPlans($with = null)
    {
        $finder = $this->finder('Host2x\Core:Plan')
            ->where('enabled', '=', true)
            ->order('order');

        if ($with)
        {
            $finder->with($with);
        }

        return $finder->fetch();
    }
}