<?php

namespace Host2x\Core\Repository;

use XF\Mvc\Entity\Repository;

class ServerPackage extends Repository
{

    public function getServerPackages(int $server_id) {
        $finder = $this->finder('Host2x\Core:ServerPackage')
            ->where('server_id', $server_id);

        return $finder->fetch();
    }

    public function getPackageServers(int $package_id) {
        $finder = $this->finder('Host2x\Core:ServerPackage')
            ->where('package_id', $package_id);

        return $finder->fetch();
    }

}