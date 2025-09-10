@echo off
REM Kill PHP artisan serve on port 8000 only if PID > 0
FOR /F "tokens=5" %%P IN ('netstat -ano ^| findstr :8000') DO (
    if not "%%P"=="0" (
        for /F "tokens=1" %%A in ('tasklist /FI "PID eq %%P" /FO CSV /NH') do (
            echo Checking PID %%P - %%~A
            if /I "%%~A"=="php.exe" (
                echo Killing PHP artisan serve with PID %%P
                taskkill /PID %%P /F
            )
        )
    )
)








@REM @echo off
@REM REM Kill PHP artisan serve on 8000
@REM FOR /F "tokens=5" %%P IN ('netstat -ano ^| findstr :8000') DO (
@REM     echo Killing PID %%P
@REM     taskkill /PID %%P /F
@REM )

@REM REM Kill all other php.exe processes (Laravel CLI)
@REM FOR /F "tokens=2" %%P IN ('tasklist ^| findstr php.exe') DO (
@REM     echo Killing PHP PID %%P
@REM     taskkill /PID %%P /F
@REM )

@REM REM Optional: give 1 second to terminate processes
@REM ping 127.0.0.1-n2>NUL



















@REM @echo off
@REM REM Kill all PHP Artisan processes on port 8000
@REM FOR /F "tokens=5" %%P IN ('netstat -ano ^| findstr :8000') DO (
@REM     echo Killing Artisan PID %%P
@REM     taskkill /PID %%P /F
@REM )

@REM REM Kill all other PHP Artisan processes (CLI processes only, not Docker)
@REM FOR /F "tokens=2" %%P IN ('tasklist ^| findstr "php.exe"') DO (
@REM     REM Avoid killing Docker-related PHP if any
@REM     tasklist /FI "PID eq %%P" /FO LIST | findstr /I "docker" >nul
@REM     IF ERRORLEVEL 1 (
@REM         echo Killing PHP PID %%P
@REM         taskkill /PID %%P /F
@REM     )
@REM )

@REM echo All Laravel/PHP commands in terminal should be killed.
@REM pause