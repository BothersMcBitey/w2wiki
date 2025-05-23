#!/bin/bash

echo "This is an attempt to commit" >> autocommit.log

# arg $0 is script name, same as python

if [ $# -eq 0 ]
  then
    echo "not enough arguments, missing commit message" >> autocommit.log
    exit 1
fi

git add ./pages/* >> autocommit.log

git commit -m "$1" >> autocommit.log

echo "Success" >> autocommit.log

exit 0
