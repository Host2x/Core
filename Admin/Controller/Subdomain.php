<?php

namespace Host2x\Core\Admin\Controller;

use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;
use XF\Admin\Controller\AbstractController;

class Subdomain extends AbstractController
{

    public function actionIndex()
    {
        $viewParams = [
            'subdomains' => $this->getSubdomainRepo()->getAllSubdomains()
        ];

        return $this->view('Host2x\Core:Subdomain\Listing', 'host2x_core_subdomain_list', $viewParams);
    }

    protected function subdomainAddEdit(\Host2x\Core\Entity\Subdomain $subdomain)
    {
        $viewParams = [
            'subdomain' => $subdomain
        ];

        return $this->view('Host2x\Core:Subdomain\Edit', 'host2x_core_subdomain_edit', $viewParams);
    }

    public function actionAdd()
    {
        /** @var \Host2x\Core\Entity\Subdomain $subdomain */
        $subdomain = $this->em()->create('Host2x\Core:Subdomain');
        return $this->subdomainAddEdit($subdomain);
    }

    public function actionEdit(ParameterBag $params)
    {
        $subdomain = $this->assertSubdomainExists($params->subdomain_id);
        return $this->subdomainAddEdit($subdomain);
    }

    protected function subdomainSaveProcess(\Host2x\Core\Entity\Subdomain $subdomain)
    {
        $form = $this->formAction();

        $input = $this->filter([
            'subdomain' => 'str',
            'enabled' => 'bool'
        ]);

        $form->basicEntitySave($subdomain, $input);
        return $form;
    }


    public function actionSave(ParameterBag $params)
    {
        if ($params->subdomain_id)
        {
            $subdomain = $this->assertSubdomainExists($params->subdomain_id);
        }
        else
        {
            /** @var \Host2x\Core\Entity\Subdomain $subdomain */
            $subdomain = $this->em()->create('Host2x\Core:Subdomain');
        }

        $this->subdomainSaveProcess($subdomain)->run();
        return $this->redirect($this->buildLink('host2x/subdomains') . $this->buildLinkHash($subdomain->subdomain_id));
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \Host2x\Core\Entity\Subdomain
     */
    protected function assertSubdomainExists($id, $with = null, $phraseKey = null)
    {
        return $this->assertRecordExists('Host2x\Core:Subdomain', $id, $with, $phraseKey ?: 'host2x_core_subdomain_not_found');
    }

    /**
     * @var \Host2x\Core\Repository\Subdomain
     */
    protected function getSubdomainRepo()
    {
        return $this->repository('Host2x\Core:Subdomain');
    }

}
