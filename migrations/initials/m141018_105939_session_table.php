<?php
use yii\db\Schema;
use yii\db\Migration;

class m141018_105939_session_table extends Migration
{

        protected function getAuthManager()
        {
            $authManager = Yii::$app->getAuthManager();
            if (!$authManager instanceof DbManager) {
                throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
            }
            return $authManager;
        }
        
	public function safeUp()
	{

            $tableOptions = null;
            if ($this->db->driverName === 'mysql') {
                $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
            }

            $this->createTable('{{%session}}', [
                    'id' => Schema::TYPE_STRING . '(40) NOT NULL',
                    'expire' => Schema::TYPE_INTEGER . '(11)',
                    'data' => Schema::TYPE_BINARY,
            ]);
           
            $this->addPrimaryKey('sessionPK', '{{%session}}', 'id' );
	}

	public function safeDown()
	{
            $this->dropTable('{{%session}}');
	}
}
