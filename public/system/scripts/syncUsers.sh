#!/bin/sh

        declare -a servers=("172.17.4.200" "172.17.4.201" "172.17.4.202" "172.17.4.203" "172.17.4.204" "172.17.4.205" "172.17.4.206" "10.116.71.13")
        ## now loop through the above array
        for i in "${servers[@]}"
        do
            sudo scp /etc/passwd "$i":/etc/passwd
            sudo scp /etc/shadow "$i":/etc/shadow
            sudo scp /etc/group "$i":/etc/group
           # or do whatever with individual element of the array
        done
        
		echo "Sync Completed"
