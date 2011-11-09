@ECHO OFF

IF NOT DEFINED MYSQL (
    CALL setup -noconsole
)

SET /P HOST=<"%CONFIG%\mysql\host"
SET /P USER=<"%CONFIG%\mysql\user"
SET /P PASS=<"%CONFIG%\mysql\pass"
SET /P PORT=<"%CONFIG%\mysql\port"

mysql -h %HOST% --user=%USER% --password=%PASS% --port=%PORT% %*

GOTO:EOF