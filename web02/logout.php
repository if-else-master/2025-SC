<?php
session_start();
session_destroy();
header('Location: guest_message.php');
exit();