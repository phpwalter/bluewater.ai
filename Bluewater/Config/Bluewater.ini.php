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
BW_VER  = 8.0ai        ; Bluewater Version

; ------------------------------------------------------------
; Normally MVC is not a path-based system; controllers are files in the
; 'Controller' directory. This flag changes the behaviour to utilize a
; directory-based controller system, with all associated files to a
; controller in the same directory as the controller.
; Modify this flag in the app level config file.
PATH_BASE = 'false'


; ------------------------------------------------------------
; Character to use as the default text string to array delimiter
ARRAY_DELIM = ,


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


; ------------------------------------------------------------
; File and Directory Modes
; These prefs used when checking and setting modes when working
; with the file system. Octal values should always be used to
; set the mode correctly.
FILE_READ_MODE   = 0644
FILE_WRITE_MODE  = 0666
DIR_READ_MODE    = 0755
DIR_WRITE_MODE   = 0777


; ------------------------------------------------------------
; Define what characters are allowed within a URI string
ALLOWED_URI_CHARS = "a-z 0-9~%.:_\-"


; ------------------------------------------------------------
; ------------------------------------------------------------
; All values defined below many be "overridden" in the application
; CONFIG file defined in 'application\Config\app.ini.php'
; ------------------------------------------------------------
; Bluewater Environment
; Different configurations depending on current environment.
; This also influences logging and error reporting.
;
; Current accepted values
;     development
;     testing
;     production
BW_ENV = development


; ------------------------------------------------------------
; General WARNING Icon to use where ever you need
WARN_ICON = "<img type="image" src="{IMAGE_PATH}{DS}icons{DS}warning.png" alt="Warning" title="Warning"/>"


; End CONSTANT SECTION
; ============================================================


; ============================================================


; eof
