<?php
session_start();           // Bắt đầu session (bắt buộc)
session_unset();           // Xóa tất cả biến session
session_destroy();         // Hủy session

header("Location: ../../../frontend/auth/login.php");
exit();
