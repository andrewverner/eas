<?php

class m161115_081728_add_scope_hash_solumn_to_user_table extends CDbMigration
{
	public function up()
	{
	    $this->addColumn('user', 'scopeHash', 'varchar(32) not null');
	}

	public function down()
	{
		echo "m161115_081728_add_scope_hash_solumn_to_user_table does not support migration down.\n";
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