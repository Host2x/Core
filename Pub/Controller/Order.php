<?php

namespace Host2x\Core\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;
use Host2x\Core\Utils\UsernameGenerator;

class Order extends AbstractController
{
    public function actionIndex()
    {
        $page = $this->filterPage();
        $perPage = 5;

        /** @var \Host2x\Core\Repository\Plan $repo */
        $repo = $this->repository('Host2x\Core:Plan');
        $finder = $repo->getEnabledPlans()
            ->limit($perPage * 3);

        $plans = $finder->fetch()->sliceToPage($page, $perPage);
        $viewParams = [
            'plans' => $plans,
            'total' => $finder->total(),
            'page' => $page,
            'perPage' => $perPage,
        ];

        return $this->view('Host2x\Core:Order\List', 'host2x_core_clientarea_plans', $viewParams);
    }

    public function actionStep1(ParameterBag $params) {
        /** @var \Host2x\Core\Entity\Plan $plan */
        $plan = $this->assertPlanExists($params->plan_id);
        $paid = false;
        if ($plan->type == 'Paid') {
            $paid = true;
        }

        $subdomains = [];

        $servers = $plan->Servers;
        if (!empty($servers)) {
            foreach ($servers as $server) {
                $serverEntity = $server->Server;
                $serverSubdomains = $serverEntity->Subdomains;
                if (!empty($serverSubdomains)) {
                    foreach ($serverSubdomains as $subdomain) {
                        if ($subdomain->Subdomain->enabled) {
                            array_push($subdomains, $subdomain->Subdomain->subdomain);
                        }
                    }
                }
            }
        }

        $viewParams = [
            'plan' => $plan,
            'subdomains' => $subdomains,
            'paid' => $paid
        ];

        return $this->view('Host2x\Core:Order\Step1', 'host2x_core_clientarea_order_step1', $viewParams);
    }

    public function actionComplete(ParameterBag $params) {
        /** @var \Host2x\Core\Entity\Plan $plan */
        $plan = $this->assertPlanExists($params->plan_id);


        $form = $this->formAction();

        $subdomain = $this->filter('subdomain', 'str', null);
        $useSubdomain = $this->filter('useSubdomain', 'bool', false);
        $domain = $this->filter('domain', 'str', null);
        $period = $this->filter('period', 'str', null);
        if ($useSubdomain && $subdomain) {
            $domain = $domain . "." . $subdomain;
        }

        // generate us a username to use
        $username = UsernameGenerator::GenerateUsername();

        if ($this->getPackageRepo()->findByDomain($domain)->fetchOne() != null) {
            return $this->message('A package with the domain '. $domain .' already exists.');
        }

        if ($this->getPackageRepo()->findByUsername($username)->fetchOne() != null) {
            return $this->message('Generated username '. $username .' already exists.');
        }

        $userId = \XF::visitor()->user_id;

        // figure out plan type
        if ($plan->type == 'P2H') {
            // determine if user needs a certain amount of posts to register.
            $requiredPosts = $plan->required_posts;
            if ($requiredPosts > 0) {
                $posts = 0;
                $postCount = $this->getThreadRepo()->findThreadsWithPostsByUser($userId)->fetch();
                $posts += $postCount->count();

                $threadsCount = $this->getThreadRepo()->findThreadsStartedByUser($userId)->fetch();
                $posts += $threadsCount->count();

                if (!($posts >= $requiredPosts)) {
                    return $this->message('You do not have the required amount of posts to order this package.');
                }
            }
        } elseif ($plan->type == 'Paid') {
            // generate an invoice
            $invoice = $this->em()->create('Host2x\Core:Invoice');
            $invoice->user_id = $userId;
            $invoice->amount = $plan->price;
            $invoice->save();
        }

        // we can now create ourselves a new package

        /** @var \Host2x\Core\Entity\Package $package */
        $package = $this->em()->create('Host2x\Core:Package');
        $package->user_id = $userId;
        $package->plan_id = $plan->plan_id;
        $package->username = $username;
        $package->domain = $domain;

        if ($plan->type == 'Paid') {
            $package->status = 'Pending';
        } else {
            $package->status = 'Active';
        }

        if ($plan->type == 'Paid') {
            $package->period = $period;
        } elseif ($plan->type == 'P2H') {
            $package->period = 'Monthly';
        } else {
            $package->period = 'Yearly'; // defaults to yearly for paid
        }

        $package->save();
        return $this->redirect($this->buildLink('clientarea'), 'Your new package will be provisioned shortly.');
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

    /**
     * @return \Host2x\Core\Repository\Package
     */
    protected function getPackageRepo()
    {
        return $this->repository('Host2x\Core:Package');
    }

    protected function packageSaveProcess(\Host2x\Core\Entity\Package $package)
    {
        $form = $this->formAction();

        $input = $this->filter([
            'plan_id' => 'uint',
            'domain' => 'str',
        ]);

        $form->basicEntitySave($package, $input);
        return $form;
    }

    /**
     * @return \XF\Repository\Thread
     */
    protected function getThreadRepo()
    {
        return $this->repository('XF:Thread');
    }
}