@echo off
title Shree Varsha Engineering Works - Web Server
color 0B
echo ========================================================
echo   SHREE VARSHA ENGINEERING WORKS - WEB APPLICATION
echo   Precision Drill Machine Manufacturers, Rajkot
echo ========================================================
echo.
echo Starting PHP Development Server on http://localhost:8080 ...
echo.
echo Website URL:     http://localhost:8080/
echo Admin Portal:    http://localhost:8080/admin/
echo Admin Login:     admin / admin123
echo.
echo Press Ctrl+C in this window to stop the server anytime.
echo ========================================================
echo.

if exist "D:\xampp\php\php.exe" (
    start http://localhost:8080/
    "D:\xampp\php\php.exe" -S localhost:8080 -t "%~dp0."
) else (
    echo [ERROR] PHP executable not found at D:\xampp\php\php.exe
    pause
)
