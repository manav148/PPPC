<?php

$pid = getmypid();
$named_pipe_path = "/var/tmp/".$pid.".pipe";

function create_named_pipe($named_pipe_path, $delete_existing = False){
    // if pipe already exists delete it
    if($delete_existing && file_exists($named_pipe_path)){
        unlink($named_pipe_path);
    }

    // Create pipe with permissions
    $success = posix_mkfifo($named_pipe_path, 0766);
    if(!$success)
    return $named_pipe_path;
}

function read_named_pipe($named_pipe_path){
    $pipe_read = fopen($named_pipe_path, 'r');
    if(!$pipe_read)
        throw Exception("Unable to read from pipe $named_pipe_path");
   
    // Again, this function blocks execution until a line is available in the pipe
    $output = fgets($pipe_read);
    return $output;
}

function process_data($data){
    // Enter logic to process data here
}

try {
    // Create named pipe to get data
    create_named_pipe($named_pipe_path);
    // Get data from named pipe
    $data = unserialize(trim(read_named_pipe($named_pipe_path)));
    
    process_data($data);
} catch (Exception $e) {
   
} finally{
    // Delete pipe 
    unlink($named_pipe_path);
}
