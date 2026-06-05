@echo off
set DB_NAME=simak_terpadu
set DB_USER=root
set DB_PASS=
set BACKUP_DIR=%~dp0..\..\storage\backups
if not exist "%BACKUP_DIR%" mkdir "%BACKUP_DIR%"
set FILE_NAME=%DB_NAME%_%DATE:~-4%%DATE:~3,2%%DATE:~0,2%_%TIME:~0,2%%TIME:~3,2%%TIME:~6,2%.sql
set FILE_NAME=%FILE_NAME: =0%
mysqldump -u %DB_USER% %DB_NAME% > "%BACKUP_DIR%\%FILE_NAME%"
echo Backup selesai: %BACKUP_DIR%\%FILE_NAME%
pause
