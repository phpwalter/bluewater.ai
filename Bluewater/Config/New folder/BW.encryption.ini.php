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
; Eventually, Bluewater will have different encryption methods,
; but for now, we only have MD5
ENCRYPT = MD5

; Used to add 'salt' to encryption routines.
; This can be anything you like, but be sure to change this
; in your application 'app.ini.php' file
SALT = mary_had_aa_little_lamb


; End CONSTANT SECTION
; ============================================================

[encryption]


; ============================================================


; eof


; eof
