<?php
use ActiveRecord\DataCache;

class ActiveRecordCacheTest extends DatabaseTest
{
	public function set_up($connection_name=null)
	{
		if (!extension_loaded('memcached'))
		{
			$this->markTestSkipped('The memcached extension is not available');
			return;
		}
		
		parent::set_up($connection_name);
		ActiveRecord\Config::instance()->set_cache('Memcached',['host'=>'localhost']);
	}

	public function tear_down()
	{
            if (DataCache::hasCache()) {
                DataCache::flush();
                DataCache::initialize(null);
            }
            $this->assert_equals(DataCache::hasCache(), false);
	}

        public function test_acpu_setup() {
                if (!extension_loaded('apcu'))
		{
			$this->markTestSkipped('The APCu extension is not available');
			return;
		}
                ActiveRecord\Config::instance()->set_cache('Apcu',[]);
        }
        
        public function test_acpu_down() {
            if (DataCache::hasCache()) {
                DataCache::flush();
                DataCache::initialize(null);
            }
            $this->assert_equals(DataCache::hasCache(), false);
        }
        
	public function test_default_expire()
	{
		$this->assert_equals(30,DataCache::$options['expire']);
	}

	public function test_explicit_default_expire()
	{
		ActiveRecord\Config::instance()->set_cache('Memcached', array('expire' => 1));
		$this->assert_equals(1,DataCache::$options['expire']);
	}

	public function test_caches_column_meta_data()
	{
		Author::first();

		$table_name = Author::table()->get_fully_qualified_table_name(!($this->conn instanceof ActiveRecord\PgsqlAdapter));
		$value = DataCache::$adapter->read("get_meta_data-$table_name");
		$this->assert_true(is_array($value));
	}
}

