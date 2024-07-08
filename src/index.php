<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );

require_once('mutex.php');
require_once('worker.php');

const TASK_TIME_IN_SECONDS = 5;

function main() {
$redis = new Redis([
	'host'=>'redis',
	'port'=>6379,
]);

if(!$redis->ping()) {
	echo "Cannot connect to Redis";
	return;
}

try{
	$mutex = new Mutex($redis,TASK_TIME_IN_SECONDS);
}catch(Exception $e) {
	echo 'Error with Mutex: ',$e->getMessage(), "<br/>";
	return;
}

$worker = new Worker($mutex);

$taskLength = TASK_TIME_IN_SECONDS;

$stubTask = function() use ($taskLength) {
    sleep($taskLength);
    return "Task completed succesfully in ".$taskLength." seconds";
};

$result = $worker->executeTaskWithMutex($stubTask);
echo $result;
}

main();
?>