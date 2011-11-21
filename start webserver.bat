@ECHO OFF

IF NOT DEFINED WEBSERVER (
    CALL scripts\setup.bat -noconsole
)

START /D "%WEBSERVER%" Start.bat