@ECHO OFF

IF NOT DEFINED MANGOS (
    CALL scripts\setup -noterminal
)

IF EXIST "%MANGOS%\bin\Win32_Release\mangosd.exe" (
    ECHO.
    ECHO -----------------------------------------------------------
    ECHO Deseas actualizar el servidor instalado con la nueva 
    ECHO compilacion?
    ECHO.
    ECHO Si aceptas, se sobreescribira el servidor ubicado en la
    ECHO carpeta server con el nuevo.
    ECHO.
    ECHO RECUERDA: TU ERES EL RESPONSABLE DE HACER COPIA DE
    ECHO SEGURIDAD DE TUS ARCHIVOS.
    ECHO -----------------------------------------------------------

    SETLOCAL EnableDelayedExpansion
    SET /P CONFIRM_INSTALL="Instalar la nueva compilacion? (s/n): "

    IF "!CONFIRM_INSTALL!"=="s" (
        COPY /Y "%MANGOS%\bin\Win32_Release\*.*" "%SERVER%\"
    )
    ENDLOCAL
)

ECHO.
ECHO --- MaNGOS debe ser compilado antes de instalarlo ---
ECHO.

CALL "%SCRIPTS%\util" sleep 2