@ECHO OFF

SET ROOT=%CD%

SET WINMINPATH=%WINDIR%;%WINDIR%\System32;%WINDIR%\System32\Wbem

SET CACHE=%ROOT%\cache
SET CONFIG=%ROOT%\config
SET MYSQL=%ROOT%\mysqlserver
SET SCRIPTS=%ROOT%\scripts
SET SERVER=%ROOT%\server
SET TOOLS=%ROOT%\tools
SET WEBSERVER=%TOOLS%\webserver

SET ACID=%CACHE%\acid
SET DATABASE=%CACHE%\database
SET MANGOS=%CACHE%\mangos
SET SD2=%MANGOS%\src\bindings\scriptdev2

SET GIT=%TOOLS%\git\bin
SET SVN=%TOOLS%\subversion
SET WGET=%TOOLS%\wget\bin

SET PATH=%WINMINPATH%;%MYSQL%\bin;%SERVER%;%TOOLS%;%GIT%;%SCRIPTS%;%SVN%;%WGET%

CALL :CHECK_PREREQUISITES

IF "%1"=="" (
    CALL "%SCRIPTS%\vc.bat" find
)

IF "%1"=="" (
    CALL "%SCRIPTS%\util.bat" showinfo
)
GOTO END


:CHECK_PREREQUISITES
IF NOT EXIST "%GIT%" (
    ECHO.
    ECHO Descargando Git ...
    ECHO ======================
    
    wget -q --no-check-certificate https://github.com/downloads/ariaththewise/easymangos/git.rar -O "%TEMP%\git.rar"
    
    IF EXIST "%TEMP%\git.rar" (
        rar x "%TEMP%\git.rar" "%TOOLS%\"
    )
    
    IF EXIST "%GIT%\git.exe" (
        ECHO Correcto.
        
    ) ELSE (
        ECHO Error.
    )
)

IF NOT EXIST "%MYSQL%" (
    ECHO.
    ECHO Descargando MySQL Server ...
    ECHO ===============================
    
    wget -q --no-check-certificate https://github.com/downloads/ariaththewise/easymangos/mysqlserver.rar -O "%TEMP%\mysqlserver.rar"
    
    IF EXIST "%TEMP%\mysqlserver.rar" (
        rar x "%TEMP%\mysqlserver.rar" "%ROOT%\"
    )
    
    IF EXIST "%MYSQL%\bin\mysqldp.exe" (
        ECHO Correcto.
        
    ) ELSE (
        ECHO Error.
    )
)

IF NOT EXIST "%SVN%" (
    ECHO.
    ECHO Descargando Subversion ...
    ECHO =============================
    
    wget -q --no-check-certificate https://github.com/downloads/ariaththewise/easymangos/svn.rar -O "%TEMP%\svn.rar"
    
    IF EXIST "%TEMP%\svn.rar" (
        rar x "%TEMP%\svn.rar" "%TOOLS%\"
    )
    
    IF EXIST "%SVN%\svn.exe" (
        ECHO Correcto.
        
    ) ELSE (
        ECHO Error.
    )
)

IF NOT EXIST "%WEBSERVER%"
    ECHO.
    ECHO Descargando el servidor web portable ...
    ECHO ===========================================
    
    wget -q --no-check-certificate https://github.com/downloads/ariaththewise/easymangos/webserver.rar -O "%TEMP%\webserver.rar"
    
    IF EXIST "%TEMP%\webserver.rar" (
        rar x "%TEMP%\webserver.rar" "%TOOLS%\"
    )
    
    IF EXIST "%WEBSERVER%\bin\httpdp.exe" (
        ECHO Correcto.
        
    ) ELSE (
        ECHO Error.
    )
)
GOTO:EOF


:CLEAN_TEMP
DEL "%TEMP%\git.rar" 2> NUL
DEL "%TEMP%\svn.rar" 2> NUL
DEL "%TEMP%\webserver.rar" 2> NUL
GOTO:EOF


:END
CALL :CLEAN_TEMP
GOTO:EOF