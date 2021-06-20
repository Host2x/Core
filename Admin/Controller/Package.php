<?php

namespace Host2x\Core\Admin\Controller;

use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;
use XF\Admin\Controller\AbstractController;

class Package extends AbstractController
{
    public function actionIndex()
    {
        $viewParams = [
            'packages' => $this->getPackageRepo()->getAllPackages()
        ];

        return $this->view('Host2x\Core:Package\Listing', 'host2x_core_package_list', $viewParams);
    }

    protected function packageAddEdit(\Host2x\Core\Entity\Package $package)
    {
        $viewParams = [
            'package' => $package
        ];

        return $this->view('Host2x\Core:Package\Edit', 'host2x_core_package_edit', $viewParams);
    }

    public function actionAdd()
    {
        /** @var \Host2x\Core\Entity\Package $package */
        $package = $this->em()->create('Host2x\Core:Package');
        return $this->packageAddEdit($package);
    }

    public function actionEdit(ParameterBag $params)
    {
        $package = $this->assertPackageExists($params->package_id);
        return $this->packageAddEdit($package);
    }

    protected function packageSaveProcess(\Host2x\Core\Entity\Package $package)
    {
        $form = $this->formAction();

        $input = $this->filter([
            'type' => 'str',
            'name' => 'str',
            'description' => 'str',
            'required_posts' => 'uint',
            'monthly_posts' => 'uint',
            'price' => 'unum',
            'order' => 'uint',
            'enabled' => 'bool'
        ]);

        $form->basicEntitySave($package, $input);
        return $form;
    }

    public function actionSave(ParameterBag $params)
    {
        if ($params->package_id)
        {
            $package = $this->assertPackageExists($params->package_id);
        }
        else
        {
            /** @var \Host2x\Core\Entity\Package $package */
            $package = $this->em()->create('Host2x\Core:Package');
        }

        $this->packageSaveProcess($package)->run();
        return $this->redirect($this->buildLink('host2x/packages') . $this->buildLinkHash($package->package_id));
    }

    public function actionServers(ParameterBag $params)
    {
        $package = $this->assertPackageExists($params->package_id);

        $criteria = $this->filter('criteria', 'array');
        $order = $this->filter('order', 'str');
        $direction = $this->filter('direction', 'str');

        $page = $this->filterPage();
        $perPage = 20;

        $showingAll = $this->filter('all', 'bool');
        if ($showingAll)
        {
            $page = 1;
            $perPage = 5000;
        }

        $searcher = $this->searcher('Host2x\Core:Server', $criteria);

        if ($order && !$direction)
        {
            $direction = $searcher->getRecommendedOrderDirection($order);
        }

        $searcher->setOrder($order, $direction);

        $finder = $searcher->getFinder();
        $finder->limitByPage($page, $perPage);

        $filter = $this->filter('_xfFilter', [
            'name' => 'str',
        ]);
        if (strlen($filter['name']))
        {
            $finder->where('name', 'LIKE', $finder->escapeLike($filter['name']));
        }

        $total = $finder->total();
        $servers = $finder->fetch();

        $viewParams = [
            'package'  => $package,
            'servers'       => $servers,

            'total'       => $total,
            'page'        => $page,
            'perPage'     => $perPage
        ];
        return $this->view('Host2x\Core:Server\Listing', 'host2x_core_package_server', $viewParams);
    }

    public function actionServersEdit(ParameterBag  $params)
    {
        $package = $this->assertPackageExists($params->package_id);

        $serverId = $this->filter('server_id', 'uint');
        $server = $this->em()->find('Host2x\Core:Server', $serverId);
        if(!$server){
            return $this->error(\XF::phrase('host2x_core_server_not_found'));
        }

    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \Host2x\Core\Entity\Package
     */
    protected function assertPackageExists($id, $with = null, $phraseKey = null)
    {
        return $this->assertRecordExists('Host2x\Core:Package', $id, $with, $phraseKey ?: 'host2x_core_package_not_found');
    }

    /**
     * @var \Host2x\Core\Repository\Package
     */
    protected function getPackageRepo()
    {
        return $this->repository('Host2x\Core:Package');
    }
}