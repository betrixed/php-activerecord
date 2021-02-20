<?php
namespace ActiveRecord;
use Closure;

/**
 * DataCache::get('the-cache-key', function() {
 *	 # this gets executed when cache is stale
 *	 return "your cacheable datas";
 * });
 */
class DataCache
{
	static $adapter = null;
	static $options = array();

	/**
	 * Initializes the cache.
	 *
	 * With the $options array it's possible to define:
	 * - expiration of the key, (time in seconds)
	 * - a namespace for the key
	 *
	 * this last one is useful in the case two applications use
	 * a shared key/store (for instance a shared Memcached db)
	 *
	 * Ex:
	 * $cfg_ar = ActiveRecord\Config::instance();
	 * $cfg_ar->set_cache('memcache://localhost:11211',array('namespace' => 'my_cool_app',
	 *																											 'expire'		 => 120
	 *																											 ));
	 *
	 * In the example above all the keys expire after 120 seconds, and the
	 * all get a postfix 'my_cool_app'.
	 *
	 * (Note: expiring needs to be implemented in your cache store.)
	 *
	 * @param string $adapter Class name, without namespace.
	 * @param array $options Specify additional options
	 */
	public static function initialize(?string $adapter, array $options=[])
	{
                
		if (!empty($adapter))
		{
			$class = "ActiveRecord\\Cache\\$adapter";
			require_once __DIR__ . "/Cache/$adapter.php";
			self::$adapter = new $class($options);
		}
		else {
			self::$adapter = null;
                }
		self::$options = array_merge(array('expire' => 30, 'namespace' => ''),$options);
	}

        public static function hasCache() : bool
        {
            return (self::$adapter !== null);
        }
	public static function flush()
	{
		if (self::$adapter)
			self::$adapter->flush();
	}

	/**
	 * Attempt to retrieve a value from cache using a key. If the value is not found, then the closure method
	 * will be invoked, and the result will be stored in cache using that key.
	 * @param $key
	 * @param $closure
	 * @param $expire in seconds
	 * @return mixed
	 */
	public static function get($key, $closure, $expire=null)
	{
		if (!self::$adapter)
			return $closure();

		if (is_null($expire))
		{
			$expire = static::$options['expire'];
		}

		$key = self::get_namespace() . $key;

		if (!($value = static::$adapter->read($key)))
			static::$adapter->write($key, ($value = $closure()), $expire);

		return $value;
	}

	public static function set($key, $var, $expire=null)
	{
		if (!static::$adapter)
			return;

		if (is_null($expire))
		{
			$expire = static::$options['expire'];
		}

		$key = self::get_namespace() . $key;
		return static::$adapter->write($key, $var, $expire);
	}

	public static function delete($key)
	{
		if (!static::$adapter)
			return;

		$key = self::get_namespace() . $key;
		return static::$adapter->delete($key);
	}

	private static function get_namespace() : string
	{
            $ns = self::$options['namespace'] ?? "";
            $ns .= "/";
            return $ns;
	}
}
