<?php

class m000000_000000_access_control extends CDbMigration
{
	public function safeUp()
	{
		// tables
		$this->createTable('auth_item', [
			'name' => 'varchar(64) not null',
			'type' => 'integer not null',
			'description' => 'text',
			'bizrule' => 'text',
			'data' => 'text',
			'primary key (`name`)',
		]);
		$this->createTable('auth_item_child', [
			'parent' => 'varchar(64) not null',
			'child' => 'varchar(64) not null',
			'primary key (`parent`,`child`)',
			'foreign key (`parent`) references `auth_item` (`name`) on delete cascade on update cascade',
			'foreign key (`child`) references `auth_item` (`name`) on delete cascade on update cascade',
		]);
		$this->createTable('auth_assignment', [
			'itemname' => 'varchar(64) not null',
			'userid' => 'varchar(64) not null',
			'bizrule' => 'text',
			'data' => 'text',
			'primary key (`itemname`,`userid`)',
			'foreign key (`itemname`) references `auth_item` (`name`) on delete cascade on update cascade',
		]);

		// default roles
		$this->insert('auth_item', [
			'name' => 'admin',
			'type' => CAuthItem::TYPE_ROLE,
			'bizrule' => 'return Yii::app()->user->isAdmin;',
		]);
		$this->insert('auth_item', [
			'name' => 'moder',
			'type' => CAuthItem::TYPE_ROLE,
			'bizrule' => 'return Yii::app()->user->isModer;',
		]);
		$this->insert('auth_item', [
			'name' => 'support',
			'type' => CAuthItem::TYPE_ROLE,
			'bizrule' => 'return Yii::app()->user->isSupport;',
		]);
		$this->insert('auth_item', [
			'name' => 'user',
			'type' => CAuthItem::TYPE_ROLE,
			'bizrule' => 'return Yii::app()->user->isUser;',
		]);
		$this->insert('auth_item', [
			'name' => 'guest',
			'type' => CAuthItem::TYPE_ROLE,
			'bizrule' => 'return Yii::app()->user->isGuest;',
		]);

		// default roles hierarchy
		$this->insert('auth_item_child', ['parent' => 'admin', 'child' => 'moder']);
		$this->insert('auth_item_child', ['parent' => 'moder', 'child' => 'support']);
	}

	public function safeDown()
	{
		$this->delete('auth_item', 'name=\'guest\'');
		$this->delete('auth_item', 'name=\'user\'');
		$this->delete('auth_item', 'name=\'support\'');
		$this->delete('auth_item', 'name=\'moderator\'');
		$this->delete('auth_item', 'name=\'admin\'');

		$this->dropTable('auth_assignment');
		$this->dropTable('auth_item_child');
		$this->dropTable('auth_item');
	}
}
