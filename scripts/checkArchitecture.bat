@ECHO OFF

IF EXIST "%SYSTEMDRIVE%\Program Files (x86)" (
    SET PROGRAM_FILES=Program Files (x86^)
    GOTO:EOF
	
) ELSE IF EXIST "%SYSTEMDRIVE%\Program Files" (
    SET PROGRAM_FILES=Program Files
    GOTO:EOF

) ELSE (
    SET PROGRAM_FILES=Archivos de programa
    GOTO:EOF
)

GOTO:EOF