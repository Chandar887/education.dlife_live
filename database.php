<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class database {

    private $db_host = "";
    private $db_user = "";
    private $db_password = "";
    private $db_database = "";
    public $con;
    //for file upload
    public $root = __DIR__;
    public $site = '/';
    public $slash = '/'; //local(\) or online(/)

    //Create Conction object

    public function __construct($h, $u, $p, $db) {
        $this->db_host = $h;
        $this->db_user = $u;
        $this->db_password = $p;
        $this->db_database = $db;
        $this->con = mysqli_connect($this->db_host, $this->db_user, $this->db_password, $this->db_database) or die(mysql_error());
    }

    function __destruct() {
        mysqli_close($this->con);
    }

    function selectRow($table, $cols = '', $condtion = '') {
        if ($cols == "") {
            $cols = "*";
        } else if (is_array($cols)) {
            $cols = implode(",", $cols);
        }
        if (isset($condtion) && $condtion != '') {
            if (is_array($condtion)) {
                $condtion = 'where ' . implode(" and ", $condtion);
            } else if (!(strpos($condtion, "where") !== false)) {
                $condtion = 'where ' . $condtion;
            }
        }

        $query = "select $cols from $table $condtion LIMIT 1";
//        echo $query;
        $result = $this->con->query($query) or die("Select Rows Error: " . mysqli_error($this->con) . " " . $query);
        if ($col_count = mysqli_num_fields($result) > 0) {
            $data = mysqli_fetch_assoc($result);
            return $data;
        } else
            return false;
    }

    //Select all row and return an array
    function selectRows($table, $cols = '', $condtion = '', $limit = '', $offset = '') {
        if ($cols == "") {
            $cols = "*";
        } else if (is_array($cols)) {
            $cols = implode(",", $cols);
        }
        if (isset($condtion) && $condtion != '') {
            if (is_array($condtion)) {
                $condtion = 'where ' . implode(" and ", $condtion);
            } else if (!(strpos($condtion, "where") !== false)) {
                $condtion = 'where ' . $condtion;
            }
        }

        if (isset($limit) && $limit != '') {
            $limit = "limit $limit";
            if (isset($offset) && $offset != '') {
                $offset = "offset $offset";
            }
        }

        $query = "select $cols from $table $condtion $limit $offset";
        $result = $this->con->query($query) or die("Select Rows Error: " . mysqli_error($this->con) . " " . $query);
        if ($col_count = mysqli_num_fields($result) > 0) {
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $data;
        } else
            return false;
    }

    //Select using query
    function selectQuery($query) {
        $result = $this->con->query($query); // or die("Select Query Error: " . mysqli_error($this->con) . " " . $query);
        if ($col_count = mysqli_num_fields($result) > 0) {
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $data;
        } else
            return false;
    }

    //Count Number of rows.
    function countRows($table, $condtion = '', $limit = '', $offset = '') {
        if (isset($condtion) && $condtion != '') {
            if (is_array($condtion)) {
                $condtion = 'where ' . implode(" and ", $condtion);
            } else if (!(strpos($condtion, "where") !== false)) {
                $condtion = 'where ' . $condtion;
            }
        }

        if (isset($limit) && $limit != '') {
            $limit = "limit $limit";
            if (isset($offset) && $offset != '') {
                $offset = "offset $offset";
            }
        }

        $query = "select count(ID) from $table $condtion $limit $offset";
        $count = 0;
        $result = $this->con->query($query) or die("Count Error: " . mysqli_error($this->con) . " " . $query);
        if (mysqli_num_fields($result) > 0) {
            $row = mysqli_fetch_array($result);
            $count = $row[0];
        }
        return $count;
    }

    //insert data
    function insertData($table, $dataarray) {
        $cols = '';
        $val = '';
        if (is_array($dataarray) && count($dataarray)) {
            $cols = implode(",", array_keys($dataarray));
            $val = "'" . implode("','", $dataarray) . "'";
        }
        $query = "insert into $table($cols) values($val)";
//        print_r($query);
        $r = $this->con->query($query) or die("Insert Error: " . mysqli_error($this->con) . " " . $query);
        if ($r == 1)
            return $this->con->insert_id;
        else
            return false;
    }

    function deleteData($table, $condtion) {
        if (isset($condtion) && $condtion != '') {
            if (is_array($condtion)) {
                $condtion = 'where ' . implode(" and ", $condtion);
            } else if (!(strpos($condtion, "where") !== false)) {
                $condtion = 'where ' . $condtion;
            }
            $query = "delete from $table $condtion";
            // echo $query;
            $r = $this->con->query($query) or die("Delete Error: " . mysqli_error($this->con) . " " . $query);
            if ($r == 1)
                return true;
            else
                return false;
        }
    }

    //update Data
    function updateData($table, $dataarray, $condtion = '') {
        $data = '';
        if (is_array($dataarray) && count($dataarray)) {
            $cols = array_keys($dataarray);
            $temp = array();
            for ($i = 0; $i < (int) count($dataarray); $i++) {
                $temp[] = $cols[$i] . "='" . $dataarray[$cols[$i]] . "'";
            }
            $data = implode(",", $temp);
        }
        if (isset($condtion) && $condtion != '') {
            if (is_array($condtion)) {
                $condtion = 'where ' . implode(" and ", $condtion);
            } else if (!(strpos($condtion, "where") !== false)) {
                $condtion = 'where ' . $condtion;
            }
        }

        $query = "update $table set $data $condtion";
        //echo $query;die;
        $r = $this->con->query($query) or die("Update Error: " . $this->con->error . " \n<br>" . $query);
        if ($r == 1)
            return true;
        else
            return false;
    }

    //file Upload
    function fileUpload($ftemppath, $dirPath, $filename, $extension) {
        //echo $filename;
        if (!file_exists($dirPath)) {
            mkdir($dirPath, 0777, true);
            touch("index.php");
        }
        $filename = $filename . '_' . time() . '.' . $extension;
        if (move_uploaded_file($ftemppath, $dirPath . $filename)) {
            return $filename;
        } else {
            return false;
        }
    }

    function str_clean($string) {
        $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9_]/', '', $string); // Removes special chars.
    }

    //file Size Check
    function imageSizeCheck($inputname, $fsize, $fwidth, $fheight) {
        // print_r($inputname);
        $fsize = $fsize * 1024;
        $image_info = getimagesize($_FILES['image']["tmp_name"]);
        $width = $image_info[0];
        $height = $image_info[1];
        //echo "{$_FILES['image']["size"]} $width $height";
        if ($_FILES['image']["size"] <= $fsize && $width == $fwidth && $height == $fheight) {
            return true;
        } else {
            return false;
        }
    }

    //Compress Image 
    function imageUploadCompressed($source, $destination, $quality) {
        $dir = explode($this->slash, $destination);
        array_pop($dir);
        $dir = implode($this->slash, $dir);
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $info = getimagesize($source);
        if ($info['mime'] == 'image/jpeg')
            $image = imagecreatefromjpeg($source);
        elseif ($info['mime'] == 'image/gif')
            $image = imagecreatefromgif($source);
        elseif ($info['mime'] == 'image/png')
            $image = imagecreatefrompng($source);
        else
            return false;
        imagejpeg($image, $destination, $quality);
        return true;
    }

    function imageRemove($url = '') {
        if (file_exists($url) && (!is_dir($url))) {
            unlink($url);
            return true;
        } else {
            return false;
        }
    }

    //order Child parent
    function flatter($node) { //support function for order category
        //Create an array element of the node     
//        echo "{$node['ID']} - ";
        $array_element = array('ID' => (int) $node['ID'],
            'cat_parent' => (int) $node['cat_parent'], 'cat_name' => (string) $node['cat_name'], 'cat_level' => (int) $node['cat_level'], 'cat_img' => (string) $node['cat_img']);
        //Add all children after me                                                                
        $result = array($array_element);
        foreach ($node['children'] as $child) {
            $result = array_merge($result, $this->flatter($child));
        }
        return $result;
    }

    function orderCategory($data) {
        $tree_node = function($id, $parent, $name, $level, $img) {
            return array('ID' => $id, 'cat_parent' => $parent, 'children' => array(), 'cat_name' => $name, 'cat_level' => $level, 'cat_img' => $img);
        };
        
        $tree = $tree_node(0, null, null, null, null, null, null); //root node                                                     
        $map = array(0 => &$tree);
        foreach ($data as $cur) {
            $id = (int) $cur['ID'];
            $parentId = (int) $cur['cat_parent'];
            $map[$id] = & $map[$parentId]['children'][];
            $map[$id] = $tree_node($id, $parentId, $cur['cat_name'], $cur['cat_level'], $cur['cat_img']);
        }
//        echo"<pre>";print_r($tree);
//        $data = $this->flatter($tree);
//        array_shift($data);
//        return $data;
        return $tree['children'];
    }

    function redirect($url = null) {
        if (is_null($url))
            $url = $this->site;
        if (headers_sent()) {
            echo "<script>window.location='" . $url . "'</script>";
        } else {
            header("Location:" . $url);
        }
        exit;
    }

    /*     * Text Encrupt Decrupt* */

    function encryptFn($data) {
        $first_key = base64_decode($this->db_password); //Any string
        $second_key = base64_decode($this->db_user); //Any string
        $method = "aes-256-cbc";
        $iv_length = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($iv_length);
        $first_encrypted = openssl_encrypt($data, $method, $first_key, OPENSSL_RAW_DATA, $iv);
        $second_encrypted = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);
        $output = base64_encode($iv . $second_encrypted . $first_encrypted);
        return $output;
    }

    function decryptFn($input) {
        $first_key = base64_decode($this->db_password);
        $second_key = base64_decode($this->db_user);
        $mix = base64_decode($input);
        $method = "aes-256-cbc";
        $iv_length = openssl_cipher_iv_length($method);
        $iv = substr($mix, 0, $iv_length);
        $second_encrypted = substr($mix, $iv_length, 64);
        $first_encrypted = substr($mix, $iv_length + 64);
        $data = openssl_decrypt($first_encrypted, $method, $first_key, OPENSSL_RAW_DATA, $iv);
        $second_encrypted_new = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);
        if (hash_equals($second_encrypted, $second_encrypted_new))
            return $data;
        return false;
    }
}

//date_default_timezone_set('Asia/Kolkata');
require_once('phpassets/vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Razorpay\Api\Api;


global $api;

function importExcel($db, $filename, $contestID = null) {
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $spreadsheet = $reader->load($filename);
    $worksheet = $spreadsheet->getSheet(0); //$spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();

    if (isset($rows[0])) {
        unset($rows[0]);
    }

    foreach ($rows as $key => $row) {
        $rowdata = array();
        if (is_array($row)) {
            if ($contestID != null) {
                $rowdata['contest_id'] = $contestID;
            }
            $rowdata['category_name'] = mysqli_real_escape_string($db->con, trim($row[0]));
            $rowdata['questions'] = mysqli_real_escape_string($db->con, trim($row[1]));
            $suggestion = array(
                mysqli_real_escape_string($db->con, trim($row[2])),
                mysqli_real_escape_string($db->con, trim($row[3])),
                mysqli_real_escape_string($db->con, trim($row[4])),
                mysqli_real_escape_string($db->con, trim($row[5]))
            );
//            $rowdata['suggestions'] = json_encode($suggestion);
            $suggestion = json_encode($suggestion);
            $rowdata['suggestions'] = mysqli_real_escape_string($db->con, trim($suggestion));
            $rowdata['answer'] = mysqli_real_escape_string($db->con, trim($row[6]));
        }
        $db->insertData("contest_que", $rowdata);
    }
    if ($contestID != null) {
        $_SESSION['que_imported'] = "Questions Are Imported Successfully!";
        header("location: ../view_exam_contest.php");
    } else {
        $_SESSION['que_imported'] = "Questions Are Imported Successfully!";
        header("location: ../view_all_questions.php");
    }
}

/* phpmailer */

function email_send($mailto, $subject, $body) {
    $smtphost = 'joycar.cab';
    $smtpsecure = 'tls'; //ssl or tls
    $smtpport = 587;  // 465 or 587 or 25
    $emailfrom = "info@joycar.cab";
    $replyto = "joycar.app@gmail.com";
    $password = "Joycar@123";
    $namefrom = "JoyCar";

    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = 0; //2 for all log, 0 for nothing
    $mail->CharSet = "UTF-8";
    $mail->Debugoutput = 'html';
    $mail->Host = $smtphost;
    $mail->SMTPSecure = $smtpsecure; //ssl or tls
    $mail->Port = $smtpport;  // 465 or 587
    $mail->SMTPAuth = true;
    $mail->SMTPOptions = array('ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
    ));
    $mail->Username = $emailfrom;
    $mail->Password = $password;
    $mail->setFrom($emailfrom, $namefrom);
    $mail->addReplyTo($replyto, $namefrom);

    $mail->Subject = $subject;
    $mail->msgHTML($body);

    if (is_array($mailto)) {
        foreach ($mailto as $email => $name) {
            $name = $name != '' ? $name : "User";
            $mail->addAddress($email, $name);
        }
    } else {
        $mail->addAddress($mailto, "User");
    }

    if (!$mail->send()) {
//            echo "Mailer Error: " . $mail->ErrorInfo;
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $headers .= 'From:JoyCar <' . $emailfrom . '>' . "\r\n";
        $headers .= 'Reply-To: ' . $emailfrom . "\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();
        return mail($mailto, $subject, $body, $headers);
//        return false;
    } else {
//            echo"Success";
        return true;
    }
}

/* * **SMS API****** */

function send_sms($mobile, $msg) {
    $baseurl = "mobicomm.dove-sms.com//submitsms.jsp";
    $key = "f5a6d1a53bXX";
    $userkey = "BHIMSAIN";
    $senderid = "INFOTP";
    $msg = urlencode($msg);
    $url = "$baseurl?user=$userkey&key=$key&senderid=$senderid&accusage=1&mobile=$mobile&message=$msg";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    $data = curl_exec($ch);
    curl_close($ch);
    echo"<script>console.log(\"$url\")</script>";
    echo"<script>console.log(\"$data\")</script>";
}

//send_sms("9780046462","this \n is a <br>test");

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full)
        $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . '' : 'just now';
}

function does_url_exists($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($code == 200) {
        $status = true;
    } else {
        $status = false;
    }
    curl_close($ch);
    return $status;
}

global $db;
if (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))) {
    $db = new database('localhost', 'root', '', 'educationpanel');
    $db->slash = "\\";
    $db->root = __DIR__ . $db->slash;
    $db->site = 'http://localhost/Dlife/';
} else {
//    $db = new database('localhost', 'ludobus1_ludo', 'KFGAjR81', 'ludobus1_ludo');
//    $db->site = 'https://ludobusiness.com/';
//    $api = new Api("rzp_live_m1vqlISlxNfHMt", "BZurHc8e5e1iwC0lybrh56O1");

//    $db = new database('localhost', '200wishe_ludonew', 'B7vJzkjR', '200wishe_ludonew');
//    $db->site = 'https://200wishes.com/ludonew/';
//    $api = new Api("rzp_test_ThzcnbvS9jN9FX", "ZJg1hxUXODP2AhtfWLmhgqBy");
    $db = new database('localhost', 'educationpanel', 'L8a9w@k5', 'educationpanel');
    $db->site = 'https://education.dlife.fun/';
//    $api = new Api("rzp_test_ThzcnbvS9jN9FX", "ZJg1hxUXODP2AhtfWLmhgqBy");
    $db->root = __DIR__ . $db->slash;
}

$request = array();
foreach ($_REQUEST as $k => $v) {
    if (is_array($v)) {
        foreach ($v as $k2 => $v2) {
            if (is_array($v2)) {
                foreach ($v2 as $k3 => $v3) {
                    $request[$k][$k2][$k3] = mysqli_real_escape_string($db->con, $v3);
                }
            } else {
                $request[$k][$k2] = mysqli_real_escape_string($db->con, $v2);
            }
        }
    } else {
        $request[$k] = mysqli_real_escape_string($db->con, $v);
    }
}

if (isset($_REQUEST)) {
    $_REQUEST = extract_post($db, $_REQUEST);
}
if (isset($_POST)) {
    $_POST = extract_post($db, $_POST);
}
if (isset($_GET)) {
    $_GET = extract_post($db, $_GET);
}

function extract_post($db, $post) {
    $var = array();
    foreach ($post as $key => $val) {
        $var[$key] = ($val);
    }
    return $var;
}

function activateUser($uid, $amount = 0, $db) {
    $return = 0;
    $uSponsor = null;
    $franchiseID = null;
    $settings = array("activateAmount" => 100, "refer_sponsor" => 10, "refer_franchise" => 10);
    if ($setingData = $db->selectRows("w_settings", "name,value", "name='activateAmount' || name='refer_sponsor' || name='refer_franchise'")) {
        foreach ($setingData as $sdata) {
            $settings[$sdata['name']] = $sdata['value'];
        }
    }
    if ($amount == 0)
        $amount = $settings['activateAmount'];
    if ($uData = $db->selectRow("w_users", "", "ID='{$uid}'")) {
        $uSponsor = $uData['uSponsor'];
        $franchiseID = $uData['franchiseID'];
    }
    if ($spData = $db->selectRow("w_sponsor_downline", "downline,level", "userID='{$uSponsor}'")) {
        $downline = $spData['downline'] . $uSponsor . "-";
        $level = $spData['level'] + 1;
        $newSp = array("userID" => $uid, "uSponsor" => $uSponsor, "downline" => $downline, "level" => $level);
        if ($db->insertData("w_sponsor_downline", $newSp)) {
            $db->updateData("w_users", array("underplaceID" => $uSponsor, "isActive" => 1, "activateDate" => date("Y-m-d H:i:s")), "ID='$uid'");
            $cData = array("uID" => $uid, "uMobile" => $uData['uMobile'], "uCoin" => $amount, "review" => "activate", 'isCredit' => 1);
            if ($coData = $db->selectRow("w_users", "uCoin", "ID='{$uid}'")) {
                $cData['beforeCoin'] = $coData['uCoin'];
                $cData['afterCoin'] = $coData['uCoin'] + $amount;
            }
            $db->insertData("w_user_coins", $cData);
            $db->con->query("update w_users set uCoin=uCoin+$amount where ID='$uid'");

            /*             * *Refer Income** */
            $ref_am = $settings['refer_sponsor'];
            $cData = array("uID" => $uSponsor, "uCoin" => $ref_am, "review" => "refer_income", "description" => "user $uid activated", "isCredit" => 1, "fromID" => $uid);
            if ($coData = $db->selectRow("w_users", "uCoin", "ID='{$uSponsor}'")) {
                $cData['beforeCoin'] = $coData['uCoin'];
                $cData['afterCoin'] = $coData['uCoin'] + $ref_am;
                $db->con->query("update w_users set uCoin=uCoin+{$ref_am} where ID='{$uSponsor}'");
                $db->insertData("w_user_coins", $cData);
            }

            if ($franchiseID != "") {
                $ref_am = $settings['refer_franchise'];
                $cData = array("uID" => $franchiseID, "uCoin" => $ref_am, "review" => "refer_income_fr", "description" => "user $uid activated", "isCredit" => 1, "fromID" => $uid);
                if ($coData = $db->selectRow("w_users", "uCoin", "ID='{$franchiseID}'")) {
                    $cData['beforeCoin'] = $coData['uCoin'];
                    $cData['afterCoin'] = $coData['uCoin'] + $ref_am;
                    $db->con->query("update w_users set uCoin=uCoin+{$ref_am} where ID='{$franchiseID}'");
                }
                $db->insertData("w_user_coins", $cData);
            }

            /*             * ********* */
            $return = 1;
        }
    }
    return $return;
}
