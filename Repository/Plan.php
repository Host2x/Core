<?php

namespace Host2x\Core\Repository;

use XF\Mvc\Entity\Repository;

class Plan extends Repository
{
    public function getPlanById($id, $with = null)
    {
        $finder = $this->finder('Host2x\Core:Plan')
            ->where('plan_id', '=', $id);

        if ($with)
        {
            $finder->with($with);
        }

        return $finder->fetch();
    }

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

        return $finder;
    }
}