<?php
/**
 * Worker class executes passed task.
 */
class Worker {
	
	private $mutex;
	
	public function __construct($mutex) {
		$this->mutex = $mutex;
	}
	
	/**
	 * executeTaskWithMutex method checks mutex whether task is already running
	 * and returns info message in the case
	 * 
	 * if task is not running, mutex is set, task executes, then mutex is cleared
	 * 
	 * @param function $taskToRun 
	 * 
	 * @return string 
	 */
	public function executeTaskWithMutex($taskToRun) {	
	    $taskInProgress = $this->mutex->isMutexSet();
	    if($taskInProgress) {
	        return "Task is in progress, cannot start";
	    }
    
	    $this->mutex->setMutex();
	    $result = $taskToRun();
		$this->mutex->clearMutex();
    
	    return $result;
	}
}
?>