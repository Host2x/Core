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

    public function getPlanServers(int $plan_id) {
        $finder = $this->finder('Host2x\Core:ServerPlan')
            ->where('plan_id', $plan_id);

        return $finder->fetch();
    }

}