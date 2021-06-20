<?php

namespace Host2x\Core;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Create;
use XF\Db\Schema\Alter;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

    /**
     * Create core tables
     */
    public function installStep1()
    {
        $schemaManager = $this->schemaManager();

        // create the tables that we need
        $schemaManager->createTable('host2x_servers', function (Create $table) {
            $table->checkExists(true);
            $table->addColumn('server_id', 'int')->autoIncrement();
            $table->addColumn('type', 'enum')->values(['WHM','DirectAdmin']);
            $table->addColumn('name', 'varchar', 255)->nullable(false);
            $table->addColumn('hostname', 'varchar', 255)->nullable(false);
            $table->addColumn('username', 'varchar', 255)->nullable(false);
            $table->addColumn('password', 'varchar', 255)->nullable(true);
            $table->addColumn('apikey', 'varchar', 255)->nullable(true);
            $table->addColumn('is_premium', 'bool')->setDefault(0);

            $table->addPrimaryKey('server_id');
        });

        $schemaManager->createTable('host2x_packages', function (Create $table){
            $table->checkExists(true);
            $table->addColumn('package_id', 'int')->autoIncrement();
            $table->addColumn('type', 'enum')->values(['Free', 'Paid', 'P2H']);
            $table->addColumn('name', 'varchar', 255)->nullable(false);
            $table->addColumn('description','mediumtext')->nullable(false);
            $table->addColumn('required_posts', 'int')->setDefault(0);
            $table->addColumn('monthly_posts', 'int')->setDefault(0);
            $table->addColumn('price', 'decimal', '10,2')->setDefault(0.00);
            $table->addColumn('order', 'int')->setDefault(0);
            $table->addColumn('enabled', 'bool')->setDefault(0);
            $table->addPrimaryKey('package_id');
        });

        $schemaManager->createTable('host2x_subdomains', function (Create $table){
            $table->checkExists(true);
            $table->addColumn('subdomain_id', 'int')->autoIncrement();
            $table->addColumn('subdomain', 'varchar', 50)->nullable(false);
            $table->addColumn('enabled', 'bool')->setDefault(0);

            $table->addPrimaryKey('subdomain_id');
        });

        $schemaManager->createTable('host2x_server_subdomains', function (Create $table){
            $table->checkExists(true);
            $table->addColumn('server_id', 'int')->unsigned(true)->nullable(false);
            $table->addColumn('subdomain_id', 'int')->unsigned(true)->nullable(false);

            $table->addPrimaryKey(['server_id', 'subdomain_id']);
        });

        $schemaManager->createTable('host2x_server_packages', function (Create $table) {
            $table->checkExists(true);
            $table->addColumn('server_id', 'int')->unsigned()->nullable(false);
            $table->addColumn('package_id', 'int')->unsigned()->nullable(false);

            $table->addPrimaryKey(['server_id', 'package_id']);
        });

        $schemaManager->createTable('host2x_user_packages', function (Create $table){
            $table->checkExists(true);
            $table->addColumn('user_package_id', 'int')->autoIncrement();
            $table->addColumn('user_id', 'int')->unsigned(true)->nullable(false);
            $table->addColumn('package_id', 'int')->unsigned(true)->nullable();
            $table->addColumn('username', 'varchar', 8)->nullable(false);
            $table->addColumn('domain', 'varchar', 255)->nullable(false);
            $table->addColumn('status', 'enum')->values([
                'Pending', 'Active', 'Suspended', 'Cancelled', 'Terminated'
            ])->setDefault('Pending');
            $table->addColumn('billing_type', 'enum')->values([
                'Monthly', 'Quarterly', 'Biquarterly', 'Yearly'
            ]);

            $table->addPrimaryKey('user_package_id');
            $table->addKey('user_id', 'user_idx');
        });

        $schemaManager->createTable('host2x_invoices', function (Create $table){
            $table->checkExists(true);
            $table->addColumn('invoice_id', 'int')->autoIncrement();
            $table->addColumn('user_id', 'int')->unsigned(true)->nullable(false);
            $table->addColumn('amount', 'decimal', '10,2')->setDefault(0.00);
            $table->addColumn('created', 'int')->setDefault(0);
            $table->addColumn('due', 'int')->setDefault(0);
            $table->addColumn('is_paid', 'bool')->setDefault(0);
            $table->addPrimaryKey('invoice_id');
        });

    }


    /**
     * Drop core tables
     */
    public function uninstallStep1()
    {
        $schemaManager = $this->schemaManager();

        // drop our tables
        $schemaManager->dropTable('host2x_invoices');
        $schemaManager->dropTable('host2x_user_packages');
        $schemaManager->dropTable('host2x_server_packages');
        $schemaManager->dropTable('host2x_server_subdomains');
        $schemaManager->dropTable('host2x_subdomains');
        $schemaManager->dropTable('host2x_packages');
        $schemaManager->dropTable('host2x_servers');
    }

}