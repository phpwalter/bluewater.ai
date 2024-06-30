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
; Special Names and Level Constants - the admin page will
; only be accessible to the user with the admin name and
; also to those users at the admin user level.
;
; Levels must be digits between 1-9.
ADMIN_NAME   = Admin
GUEST_NAME   = Guest
SYSTEM_LEVEL = 10
ADMIN_LEVEL  = 9
MGR_LEVEL    = 3
SPVR_LEVEL   = 5
USER_LEVEL   = 1
GUEST_LEVEL  = 0

; ------------------------------------------------------------
; This boolean constant controls whether or not the script
; keeps track of active users and active guests who are
; visiting the site.
TRACK_VISITORS = 'false'

; End CONSTANT SECTION
; ============================================================

[user]


; ============================================================


; eof


; eof
