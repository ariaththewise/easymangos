@ECHO OFF

IF NOT DEFINED MANGOS (
    CALL scripts\setup.bat
)

IF "%VCVER%"=="NULL" (
    CALL :VC_NOT_FOUND
    GOTO :END
)


IF EXIST "%MANGOS%\win\mangosdVC%VCVER%.sln" (
    ECHO.
    ECHO Compilando MaNGOS ...
    ECHO ====================
    
    CALL scripts\vc build "%MANGOS%\win\mangosdVC%VCVER%.sln"
    GOTO END
    
) ELSE (
    CALL :MANGOS_NOT_FOUND
    GOTO END
)


:MANGOS_NOT_FOUND
ECHO.
ECHO --- No se ha encontrado MaNGOS ---
ECHO.
GOTO:EOF


:VC_NOT_FOUND
ECHO.
ECHO --- No se ha encontrado Visual C++ ---
ECHO.
GOTO:EOF


:END
CALL scripts\util sleep 2
GOTO:EOF