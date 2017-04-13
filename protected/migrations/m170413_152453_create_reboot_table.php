<?php

class m170413_152453_create_reboot_table extends CDbMigration
{
	public function up()
	{
            $this->createTable('reboot', array(
            'id' => 'pk',
            'ip' => 'string NOT NULL',
            'st1' => 'string NOT NULL',
            'st2' => 'string NOT NULL',
            'date' => 'integer NOT NULL',
        ));
	}

	public function down()
	{
		$this->dropTable('reboot');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}