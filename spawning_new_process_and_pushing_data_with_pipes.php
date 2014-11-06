<?php

function write_to_named_pipe($named_pipe_path, $serialized_data){
    $pipe_write = fopen($named_pipe_path, 'w');
    if(!$pipe_write)
        throw Exception("Unable to write from pipe $named_pipe_path");
    // Write to the pipe
    // This normally blocks execution of the code until another process reads everything we wrote
    fwrite($pipe_write, $serialized_data."\n");
    fclose($pipe_write);
}   


$program_to_execute = "dummy.php";
$arguments_to_send = array();
// Run publishing in the background
$cmd = "nohup php $program_to_execute > /dev/null 2>&1 & echo $!";
$pid = exec($cmd);
$named_pipe_path = "/var/tmp/".$pid.".pipe";
$arguments_to_send["argument1"] = "argument1";
$arguments_to_send["argument2"] = "argument2";
write_to_named_pipe($named_pipe_path, serialize($arguments_to_send));
