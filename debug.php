<?php
    echo('<a href = home.php>home.php</a><br>');
    echo('<a href = logic/Logout.php>logic/Logout.php</a><br><br>');

    session_start();
    var_dump($_SESSION);
?>