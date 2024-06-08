# Bluewater.ai

It's about time we join the AI bandwagon!

## Install

Run "composer check-platform-reqs" to ensure your environment has everything it needs


## Rewrite Engine
## Apache config file for Bluewater

```
#################################################
# bluewater.local

<VirtualHost bluewater.local:80>

   define VHNAME bluewater

   ServerName    ${VHNAME}.local:80
   ServerAdmin   walter@${VHNAME}
   ServerAlias   *.${VHNAME}
   
   DocumentRoot  ${VHROOT}/${VHNAME}/htdocs/
#   ScriptAlias   ${VHROOT}/${VHNAME} ${VHROOT}/${VHNAME}/cgi-bin

   # LogLevel: Control the severity of messages logged to the error_log.
   # Available values: trace8, ..., trace1, debug, info, notice, warn,
   # error, crit, alert, emerg.
   # It is also possible to configure the log level for particular modules, e.g.
   # "LogLevel info ssl:warn"
   LogLevel                debug

   ErrorLog      ${ERRLOGDIR}/${VHNAME}.error.log
   TransferLog   ${ERRLOGDIR}/${VHNAME}.access.log
#   ScriptLog     ${ERRLOGDIR}/${VHNAME}.cgi_error.log

   <FilesMatch "\.(cgi|html|php)$">
      SSLOptions +StdEnvVars
   </FilesMatch>

   <Directory />
       Options Indexes FollowSymLinks IncludesNoExec
        AllowOverride All
        Options None
        Require all granted
   </Directory>

   RewriteEngine on

   # Handle Front Controller...
   RewriteCond %{DOCUMENT_ROOT}%{REQUEST_FILENAME} !-f
   RewriteCond %{DOCUMENT_ROOT}%{REQUEST_FILENAME} !-d
   RewriteRule ^ /index.php [L,QSA]

</VirtualHost>

# eof
```
