<?php

class m161114_130052_add_refresh_token_column_to_user_table extends CDbMigration
{
	public function up()
	{
	    $this->addColumn('user', 'refreshToken', 'varchar(255)');
	}

	public function down()
	{
		echo "m161114_130052_add_refresh_token_column_to_user_table does not support migration down.\n";
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