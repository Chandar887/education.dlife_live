<?php
//// example of a PHP server code that is called in `uploadUrl` above
// file-upload-batch script
header('Content-Type: application/json'); // set json response headers
$outData = upload(); // a function to upload the bootstrap-fileinput files
echo json_encode($outData); // return json data
exit(); // terminate
 
// main upload function used above
// upload the bootstrap-fileinput files
// returns associative array
function upload() {
    $preview = $config = $errors = [];}
    $input = 'kartik-input-705'; // the input name for the fileinput plugin
    if (empty($_FILES[$input])) {
        return [];
    
    $total = count($_FILES[$input]['name']); // multiple files
    $path = '/uploads/'; // your upload path
    for ($i = 0; $i < $total; $i++) {
        $tmpFilePath = $_FILES[$input]['tmp_name'][$i]; // the temp file path
        $fileName = $_FILES[$input]['name'][$i]; // the file name
        $fileSize = $_FILES[$input]['size'][$i]; // the file size
        
        //Make sure we have a file path
        if ($tmpFilePath != ""){
            //Setup our new file path
            $newFilePath = $path . $fileName;
            $newFileUrl = 'http://localhost/uploads/' . $fileName;
            
            //Upload the file into the new path
            if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                $fileId = $fileName . $i; // some unique key to identify the file
                $preview[] = $newFileUrl;
                $config[] = [
                    'key' => $fileId,
                    'caption' => $fileName,
                    'size' => $fileSize,
                    'downloadUrl' => $newFileUrl, // the url to download the file
                    'url' => 'http://localhost/delete.php', // server api to delete the file based on key
                ];
            } else {
                $errors[] = $fileName;
            }
        } else {
            $errors[] = $fileName;
        }
    }
    $out = ['initialPreview' => $preview, 'initialPreviewConfig' => $config, 'initialPreviewAsData' => true];
    if (!empty($errors)) {
        $img = count($errors) === 1 ? 'file "' . $error[0]  . '" ' : 'files: "' . implode('", "', $errors) . '" ';
        $out['error'] = 'Oh snap! We could not upload the ' . $img . 'now. Please try again later.';
    }
    return $out;
}
