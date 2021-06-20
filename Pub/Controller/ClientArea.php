<?php

namespace Host2x\Core\Pub\Controller;

use XF\Pub\Controller\AbstractController;

class ClientArea extends AbstractController
{
    public function actionIndex() {
        /** @var \Host2x\Core\Repository\UserPackage $repo */
        $repo = $this->repository('Host2x\Core:UserPackage');
        $finder = $repo->findPackagesForUser();
        $packageCount = $finder->total();

        $unpaidInvoiceCount = $this->repository('Host2x\Core:Invoice')->getUnpaidInvoicesCount();

        $viewParams = [
            'packageCount' => $packageCount,
            'unpaidInvoiceCount' => $unpaidInvoiceCount
        ];

        return $this->view('Host2x\Core:View', 'host2x_core_clientarea', $viewParams);
    }

}