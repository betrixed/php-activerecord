<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ActiveRecord\Cache;
use function apcu_enabled, apcu_fetch, apcu_store, apcu_delete, apcu_clear_cache;
/**
 * Description of ApcuCache
 *
 * @author michael
 */
class Apcu {
	/**
	 * Uses the APCU cache
	 * No options yet
	 *
	 * @param array $options
	 */
	public function __construct(array $options)
	{
            if (!function_exists("\\apcu_enabled") || !apcu_enabled() ) {
                throw new CacheException('APCu Cache is not enabled');
            }
	}

	public function flush()
	{
		apcu_clear_cache( );
	}

	public function read(string $key)
	{
            $success = false;
            $result = apcu_fetch($key, $success);
            if ($success) 
            {
                return $result;
            }
            else {
                return null;
            }
	}

	public function write(string $key, $value, int $expire)
	{
		apcu_store($key,$value,$expire);
	}

	public function delete(string $key)
	{
		apcu_delete($key);
	}
}
