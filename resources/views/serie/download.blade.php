<?php

if(!empty($ods)){
    $ods->load($data);
    return $ods->stream($filename);
    exit;
}


$delimiter=';';

// open raw memory as file so no temp files needed, you might run out of memory though
$f = fopen('php://memory', 'w');
// loop over the input array
foreach ($data as $line) {
    // generate csv lines from the inner arrays
    fputcsv($f, $line, $delimiter);
}
// reset the file pointer to the start of the file
fseek($f, 0);
// tell the browser it's going to be a csv file
header('Content-Type: application/csv');
// tell the browser we want to save it instead of displaying it
header('Content-Disposition: attachment; filename="'.$filename.'";');
// make php send the generated csv lines to the browser
fpassthru($f);

//echo "<script>window.close();</script>";





//$users = User::select('id', 'name', 'email', 'created_at')->get();
/*Excel::create('valores', function($excel) use($data) {
    $excel->sheet('Sheet 1', function($sheet) use($data) {
        $sheet->fromArray($data);
    });
})->export('ods');*/