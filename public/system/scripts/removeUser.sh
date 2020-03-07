#!/bin/sh
user=$1
removeHome=$2
if [ -z $user ]
then
    echo "please provide username as an argument."
else
	userExists=`cut -d: -f1 /etc/passwd | grep -w $user`
	if [ -z $userExists ]
	then
		echo "User does not exist"
	else
		echo 
		if [ -z $removeHome ]
	    then
            sudo userdel -f $user
            sudo groupdel $user
        else
            sudo userdel -f -r $user
            sudo groupdel $user
        fi
        userExists=`cut -d: -f1 /etc/passwd | grep -w $user`
	    if [ -z $userExists ]
	    then
            echo "User removed"
        else
            echo "User could not be removed"
        fi
	fi
fi