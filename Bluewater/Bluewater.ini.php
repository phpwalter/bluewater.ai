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
; Time - seconds per
MINUTE = 60
HOUR = 3600
DAY = 86400


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


; ------------------------------------------------------------
; Eventually, Bluewater will have different encryption methods,
; but for now, we only have MD5
ENCRYPT = MD5

; Used to add 'salt' to encryption routines.
; This can be anything you like, but be sure to change this
; in your application 'app.ini.php' file
SALT = mary_had_aa_little_lamb

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


; ------------------------------------------------------------
; This boolean constant controls whether or not the script
; keeps track of active users and active guests who are
; visiting the site.
TRACK_VISITORS = 'false'

; ------------------------------------------------------------
; Special Names and Level Constants - the admin
; page will only be accessible to the user with
; the admin name and also to those users at the
; admin user level.
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
; Email Constants - these specify what goes in
; the FROM field in the emails that the script
; sends to users, and whether to send a
; welcome email to newly registered users.
EMAIL_FROM_NAME  = Administrator
EMAIL_FROM_ADDR  = admin@
EMAIL_WELCOME    = 'true'


; ------------------------------------------------------------
A map of mimetypes to their output format.
In order to add a new mimetype, add it's mimetype name
and then add it's output as the associated value.

XML_APP    = 'application/xml'
XML        = 'text/xml'
JSON_APP   = 'application/json'
JSON       = 'text/json'
HTML       = 'text/html'
JAVASCRIPT = 'text/javascript'
CSS        = 'text/css'
TEXT       = 'text/plain'


; ------------------------------------------------------------
; DOC Types can be:
; - h4.1 = HTML 4.1 strict
; - h5   = HTML 5
; - x1   = xHTML 1.0 strict
; - x1.1 = xHTML 1.1 strict
DOC_TYPE = x1


; ------------------------------------------------------------
; General WARNING Icon to use where ever you need
WARN_ICON = "<img type="image" src="{IMAGE_PATH}{DS}icons{DS}warning.png" alt="Warning" title="Warning"/>"


; End CONSTANT SECTION
; ============================================================


[general]
tz = America/Chicago

; ============================================================


; eof
