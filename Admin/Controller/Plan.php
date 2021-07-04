<?php

namespace Host2x\Core\Admin\Controller;

use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;
use XF\Admin\Controller\AbstractController;

class Plan extends AbstractController
{
    public function actionIndex()
    {
        $viewParams = [
            'plans' => $this->getPlanRepo()->getAllPlans()
        ];

        return $this->view('Host2x\Core:Plan\Listing', 'host2x_core_plan_list', $viewParams);
    }

    protected function planAddEdit(\Host2x\Core\Entity\Plan $plan)
    {
        $viewParams = [
            'plan' => $plan
        ];

        return $this->view('Host2x\Core:Plan\Edit', 'host2x_core_plan_edit', $viewParams);
    }

    public function actionAdd()
    {
        /** @var \Host2x\Core\Entity\Plan $plan */
        $plan = $this->em()->create('Host2x\Core:Plan');
        return $this->planAddEdit($plan);
    }

    public function actionEdit(ParameterBag $params)
    {
        $plan = $this->assertPlanExists($params->plan_id);
        return $this->planAddEdit($plan);
    }

    protected function planSaveProcess(\Host2x\Core\Entity\Plan $plan)
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

        $form->basicEntitySave($plan, $input);
        return $form;
    }

    public function actionSave(ParameterBag $params)
    {
        if ($params->plan_id)
        {
            $plan = $this->assertPlanExists($params->plan_id);
        }
        else
        {
            /** @var \Host2x\Core\Entity\Plan $plan */
            $plan = $this->em()->create('Host2x\Core:Plan');
        }

        $this->planSaveProcess($plan)->run();
        return $this->redirect($this->buildLink('host2x/plans') . $this->buildLinkHash($plan->plan_id));
    }

    public function actionServers(ParameterBag $params)
    {
        $plan = $this->assertPlanExists($params->plan_id);

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
            'plan'  => $plan,
            'servers'       => $servers,

            'total'       => $total,
            'page'        => $page,
            'perPage'     => $perPage
        ];

        return $this->view('Host2x\Core:Server\Listing', 'host2x_core_plan_server', $viewParams);
    }

    public function actionServersAdd(ParameterBag $params)
    {
        return $this->actionServersEdit($params);
    }

    public function actionServersEdit(ParameterBag  $params)
    {
        $plan = $this->assertPlanExists($params->plan_id);

        $serverId = $this->filter('server_id', 'uint');
        $server = $this->em()->find('Host2x\Core:Server', $serverId);
        
        if (!$server) {
            return $this->error(\XF::phrase('host2x_core_server_not_found'));
        }

    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \Host2x\Core\Entity\Plan
     */
    protected function assertPlanExists($id, $with = null, $phraseKey = null)
    {
        return $this->assertRecordExists('Host2x\Core:Plan', $id, $with, $phraseKey ?: 'host2x_core_plan_not_found');
    }

    /**
     * @var \Host2x\Core\Repository\Plan
     */
    protected function getPlanRepo()
    {
        return $this->repository('Host2x\Core:Plan');
    }
}