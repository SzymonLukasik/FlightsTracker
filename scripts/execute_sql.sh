#!/bin/sh

username=$1
password=$2
dsn=$3
script_path=$4
logfile=`pwd`/scripts/shell_log.txt
touch $logfile

sqlplus -s /nolog <<-EOF> ${logfile}
WHENEVER OSERROR EXIT 9;
WHENEVER SQLERROR EXIT SQL.SQLCODE;
connect $username/$password@$dsn
--SET SERVEROUTPUT ON;
--DBMS_OUTPUT.PUT_LINE('Connected to database.');
@$script_path
EOF

sql_return_code=$?

if [ $sql_return_code != 0 ]
then
echo "The upgrade script failed. Please refer to the shell_log.txt for more information"
echo "Error code $sql_return_code"
exit 0;
fi