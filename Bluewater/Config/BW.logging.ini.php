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
; Logging settings. This is turned full on for development
; 'turn off' these levels in 'application\Config\app.ini.php' file
LOGGER_SQL   = 'false'
LOGGER_ERR   = 'false'
LOGGER_TRACE = 'false'
LOG_PATH     = {CACHE_ROOT}{DS}logs

; PHP error logging values
; http://php.net/manual/en/function.error-log.php
ERR_SYS_LOG    = 0        ;; PHP's system logger
ERR_EMAIL      = 1        ;; sent to email address as defined below
ERR_EMAIL_ADDR = errors@  ;; email address to send error to
; #2 is not defined
ERR_FILE       = 3        ;; Send to app defined error log
ERR_FILE_PATH  = {LOG_PATH}{DS}php_errors.log


; End CONSTANT SECTION
; ============================================================

[logging]


; ============================================================


; eof


; eof
