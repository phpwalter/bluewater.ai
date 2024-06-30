<?php

// Make sure this file can be not directly opened.
// DO NOT remove this EXIT. It is here for security reasons
exit;

?>
; This is the settings page, do not modify the above PHP code.

; Section headers are REQUIRED

; Do not change any settings in this file to satisfy a particular
; project requirements. Config settings in the LOCAL Application
; INI file will override the settings in this config file.

; ============================================================
; This section needs to be first in this file
[constants]

; ------------------------------------------------------------
; Cookie Constants - these are the parameters to the setcookie
; function call, change them if necessary to fit your website
; If you need help, visit www.php.net for more info.
;
<http://www.php.net/manual/en/function.setcookie.php>

COOKIE_EXPIRE  = {DAY}*10   ; 10 days by default
COOKIE_PATH    = /          ; Available in whole domain


; ------------------------------------------------------------
; This determines if a SESSION is to be created, and which
; type of session: false, server, cookie
;   'false'  - no session to be created
;   'server' - standard Server based sessions
;   'cookie' - cookie based sessions for load balanced serves
SESSION = 'false'


; End CONSTANT SECTION
; ============================================================

[session]


; ============================================================


; eof


; eof
