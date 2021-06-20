<?php

namespace Host2x\Core\Admin\Controller;

use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;
use XF\Admin\Controller\AbstractController;

class Server extends AbstractController
{
    public function actionIndex()
    {
        $viewParams = [
            'servers' => $this->getServerRepo()->getServers()
        ];

        return $this->view('Host2x\Core:Server\Listing', 'host2x_core_server_list', $viewParams);
    }

    protected function serverAddEdit(\Host2x\Core\Entity\Server $server)
    {
        $viewParams = [
            'server' => $server
        ];

        return $this->view('Host2x\Core:Server\Edit', 'host2x_core_server_edit', $viewParams);
    }

    public function actionAdd()
    {
        /** @var \Host2x\Core\Entity\Server $server */
        $server = $this->em()->create('Host2x\Core:Server');
        return $this->serverAddEdit($server);
    }

    public function actionEdit(ParameterBag $params)
    {
        $server = $this->assertServerExists($params->server_id);
        return $this->serverAddEdit($server);
    }

    protected function serverSaveProcess(\Host2x\Core\Entity\Server $server)
    {
        $form = $this->formAction();

        $input = $this->filter([
            'type' => 'str',
            'name' => 'str',
            'hostname' => 'str',
            'username' => 'str',
            'password' => 'str',
            'apikey' => 'str',
            'is_premium' => 'bool'
        ]);

        if ($server->isUpdate() && empty($input['password']))
        {
            unset($input['password']);
        }

        $form->basicEntitySave($server, $input);
        return $form;
    }


    public function actionSave(ParameterBag $params)
    {
        if ($params->server_id)
        {
            $server = $this->assertServerExists($params->server_id);
        }
        else
        {
            /** @var \Host2x\Core\Entity\Server $server */
            $server = $this->em()->create('Host2x\Core:Server');
        }

        $this->serverSaveProcess($server)->run();
        return $this->redirect($this->buildLink('host2x/servers') . $this->buildLinkHash($server->server_id));
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \Host2x\Core\Entity\Server
     */
    protected function assertServerExists($id, $with = null, $phraseKey = null)
    {
        return $this->assertRecordExists('Host2x\Core:Server', $id, $with, $phraseKey ?: 'host2x_core_server_not_found');
    }

    /**
     * @var \Host2x\Core\Repository\Server
     */
    protected function getServerRepo()
    {
        return $this->repository('Host2x\Core:Server');
    }
}