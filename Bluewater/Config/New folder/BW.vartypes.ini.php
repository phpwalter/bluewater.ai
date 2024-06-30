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
; Variable types
TYPE_UNKNOWN      = -1
TYPE_NULL         =  0
TYPE_INT          =  1
TYPE_INTEGER      =  1
TYPE_LONG         =  2   ; not yet implemented
TYPE_STRING       =  3
TYPE_FLOAT        =  4
TYPE_DOUBLE       =  5   ; not yet implemented
TYPE_BOOL         =  6
TYPE_ARRAY        =  7
TYPE_OBJECT       =  8
TYPE_OUTPUT       =  9
TYPE_FILE         = 10
TYPE_XML          = 11
TYPE_JSON         = 12
TYPE_ALPHA        = 13
TYPE_ALPHANUM     = 14
TYPE_ALPHANUMERIC = 14

; End CONSTANT SECTION
; ============================================================


[database]
supported = MySQL

; ============================================================


; eof
