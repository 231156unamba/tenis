@echo off
REM Arranque rápido del servidor PHP embebido
REM Requiere tener PHP instalado y agregado al PATH
cd /d %~dp0
php -S localhost:8080
pause