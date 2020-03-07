#!/bin/sh
user=$1
if [ -z $user ]
then
    echo "please provide username as an argument."
else
	userExists=`cut -d: -f1 /etc/passwd | grep -w $user`
	if [ -z $userExists ]
	then
		echo 0
	else
		echo 1
	fi
fi