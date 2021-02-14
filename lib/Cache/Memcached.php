<?php
namespace ActiveRecord\Cache;

class Memcached
{
	const DEFAULT_PORT = 11211;
        const DEFAULT_HOST = 'localhost';
	private $memcache;

	/**
	 * Creates a Memcache instance.
	 *
	 * Takes an $options array w/ the following parameters:
	 *
	 * <ul>
	 * <li><b>host:</b> host for the memcache server </li>
	 * <li><b>port:</b> port for the memcache server </li>
	 * </ul>
	 * @param array $options
	 */
	public function __construct(array $options)
	{
		$this->memcache = new \Memcached();
                
                $port = $options['port'] ?? self::DEFAULT_PORT;
                $host = $options['host'] ?? self::DEFAULT_HOST;
                
		if (!$this->memcache->addServer($host, $port)) {
			$message = sprintf('Could not connect to %s:%s', $host, $port);
			throw new CacheException($message);
		}
	}

	public function flush()
	{
		$this->memcache->flush();
	}

	public function read($key)
	{
		return $this->memcache->get($key);
	}

	public function write($key, $value, $expire)
	{
		$this->memcache->set($key,$value,$expire);
	}

	public function delete($key)
	{
		$this->memcache->delete($key);
	}
}
