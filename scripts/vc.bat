@ECHO OFF

IF "%1"=="find" (
    CALL:find
    GOTO END
)

SET OPTIONS=%*

IF NOT "%VCPATH%"=="" (
    IF NOT "%VCPATH%"=="NULL" (        
        ECHO.
        ECHO Visual C++: Trabajando ...
        
        "%VCPATH%" %OPTIONS%
        
        ECHO.
        ECHO Visual C++: Terminado
        
        GOTO END
    )
)

GOTO END


:find
CALL "%SCRIPTS%\checkArchitecture.bat"

SET VS2008_ROOT=%SYSTEMDRIVE%\%PROGRAM_FILES%\Microsoft Visual Studio 9.0\Common7\IDE
SET VS2010_ROOT=%SYSTEMDRIVE%\%PROGRAM_FILES%\Microsoft Visual Studio 10.0\Common7\IDE

IF EXIST "%VS2010_ROOT%\devenv.exe" (    
    SET VCNAME=Visual C++ 2010 Professional
    SET VCPATH=%VS2010_ROOT%\devenv.exe
    SET LATEST_VC=2010    
    GOTO:EOF
	
) ELSE IF EXIST "%VS2010_ROOT%\VCExpress.exe" (
    SET VCNAME=Visual C++ 2010 Express
    SET VCPATH=%VS2010_ROOT%\VCExpress.exe
    SET LATEST_VC=2010    
    GOTO:EOF
	
) ELSE IF EXIST "%VS2008_ROOT%\devenv.exe" (
    SET VCNAME=Visual C++ 2008 Professional
    SET VCPATH=%VS2008_ROOT%\devenv.exe
    SET LATEST_VC=2008
    GOTO:EOF
	
) ELSE IF EXIST "%VS2008_ROOT%\VCExpress.exe" (
    SET VCNAME=Visual C++ 2008 Express
    SET VCPATH=%VS2008_ROOT%\VCExpress.exe
    SET LATEST_VC=2008    
    GOTO:EOF

)

ECHO No se pudo encontrar Visual C++ en las rutas por defecto.
ECHO Puedes indicar una ruta manualmente si quieres (Se agregaran dobles comillas automaticamente).
ECHO (NOTA: Los ejecutables validos son tanto devenv.exe como VCExpress.exe)
ECHO.
SET /P VC_CUSTOM_PATH_CONFIRM=Establecer ruta a mano (s/n)?

IF "%VC_CUSTOM_PATH_CONFIRM%"=="s" (
    SET /P VC_NAME=Nombre de la version: 
	SET /P VC_PATH=Ruta de Visual C++: 
	SET /P VC_VERSION=Version de Visual C++ (2005, 2008, 2010): 
	
	IF EXIST "%VC_PATH%" (        
        SET VCNAME=%VC_NAME%
	    SET VCPATH=%VC_PATH%
		SET LATEST_VC=%VC_VERSION%
		
		GOTO:EOF
	)
)

SET VCNAME=No encontrado
SET VCPATH=NULL
SET LATEST_VC=NULL

GOTO:EOF

:END
GOTO:EOF