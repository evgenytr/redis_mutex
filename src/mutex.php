<?php
/**
 * Mutex class uses Redis to set/clear key for designated lock time.
 */
class Mutex {
	private const MUTEX_KEY = "3937a69fb886f5902bf5a4954772bc8e";
	private const MUTEX_EXPIRATION_TIME_DEFAULT = 1;
	
	private $redis;
	private $mutexExpirationTimeInSeconds = Mutex::MUTEX_EXPIRATION_TIME_DEFAULT;
	
	public function __construct($redis, $mutexExpirationTime = null) {
	    $this->redis = $redis;
			
		if($mutexExpirationTime!=null && $mutexExpirationTime!=0)	
			$this->mutexExpirationTimeInSeconds = $mutexExpirationTime;
		
		if(!$this->redis->ping()) {
				throw new Exception("Cannot connect to Redis");
			}
	}
	
	public function setMutex() {
	    $this->redis->set(Mutex::MUTEX_KEY,'mutex', ['nx','ex'=>$this->mutexExpirationTimeInSeconds]);
	}

	public function clearMutex() {
	    $this->redis->unlink(Mutex::MUTEX_KEY);
	}

	public function isMutexSet() {
	    return $this->redis->get(Mutex::MUTEX_KEY);
	} 
}
?>