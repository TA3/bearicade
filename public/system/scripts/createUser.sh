#!/bin/sh
user=$1
if [ -z $user ]
then
    echo "please provide username as an argument."
else
	userExists=`cut -d: -f1 /etc/passwd | grep -w $user`
	if [ -z $userExists ]
	then
		sudo useradd $user
		sudo -H -u $user bash -c 'ssh-keygen -f $HOME/.ssh/id_rsa -t rsa -N ""' >/dev/null
		sudo -H -u $user bash -c 'cat $HOME/.ssh/id_rsa.pub > $HOME/.ssh/authorized_keys' >/dev/null
        sudo cp /home/$user/.ssh/id_rsa /keys_rsa/$user 
        sudo chmod 644 /keys_rsa/$user
        sudo  chown -R $user:$user /home/$user
        sudo usermod -a -G bearicader $user
        userExists=`cut -d: -f1 /etc/passwd | grep -w $user`
	    if [ -z $userExists ]
	    then
            echo "User could not be created"
        else
            echo "User has been created"
        fi
	else
		echo "Error: User Exists"
	fi
fi