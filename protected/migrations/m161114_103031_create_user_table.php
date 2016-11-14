<?php

class m161114_103031_create_user_table extends CDbMigration
{
	public function up()
	{
	    $this->createTable('user',[
	        'id' => 'pk',
            'characterID' => 'int not null',
            'characterName' => 'varchar(255) not null',
            'accessToken' => 'varchar(255) not null',
            'expiresOn' => 'datetime not null',
            'scopes' => 'varchar'
        ],'charset=utf8');
	}

	public function down()
	{
		echo "m161114_103031_create_user_table does not support migration down.\n";
		return false;
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