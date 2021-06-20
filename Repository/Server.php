<?php

namespace Host2x\Core\Repository;

use XF\Mvc\Entity\Repository;

class Server extends Repository
{
    public function getServers($with = null)
    {
        $finder = $this->finder('Host2x\Core:Server');

        if ($with)
        {
            $finder->with($with);
        }

        return $finder->fetch();
    }

    public function getFreeServers($with = null) {
        $finder = $this->finder('Host2x\Core:Server')
            ->where('is_premium', 0);

        if ($with)
        {
            $finder->with($with);
        }

        return $finder->fetch();
    }

    public function getPremiumServers($with = null) {
        $finder = $this->finder('Host2x\Core:Server')
            ->where('is_premium', 1);

        if ($with)
        {
            $finder->with($with);
        }

        return $finder->fetch();
    }
}