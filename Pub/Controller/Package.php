<?php

namespace Host2x\Core\Pub\Controller;

use XF\Pub\Controller\AbstractController;

class Package extends AbstractController
{
    public function actionIndex() {
        $page = $this->filterPage();
        $perPage = 5;

        /** @var \Host2x\Core\Repository\Package $repo */
        $repo = $this->repository('Host2x\Core:UserPackage');
        $finder = $repo->findPackagesForUser()
            ->limit($perPage * 3);

        $packages = $finder->fetch()->sliceToPage($page, $perPage);
        $viewParams = [
            'packages' => $packages,
            'total' => $finder->total(),
            'page' => $page,
            'perPage' => $perPage,
        ];

        return $this->view('Host2x\Core:Package\List', 'host2x_core_clientarea_packages', $viewParams);
    }
}