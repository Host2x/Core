<?php

namespace Host2x\Core\Repository;

use XF\Mvc\Entity\Repository;

class ServerPlan extends Repository
{

    public function getServerPlans(int $server_id) {
        $finder = $this->finder('Host2x\Core:ServerPlan')
            ->where('server_id', $server_id);

        return $finder->fetch();
    }

    public function getPlanServers(int $package_id) {
        $finder = $this->finder('Host2x\Core:ServerPlan')
            ->where('package_id', $package_id);

        return $finder->fetch();
    }

}