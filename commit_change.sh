#!/bin/bash

echo "This is an attempt to commit" >> commit.log

# arg $0 is script name, same as python

if [ $# -eq 0 ]
  then
    echo "not enough arguments, missing commit message" >> commit.log
    exit 1
fi

git add ./pages/* >> commit.log

git commit -m "$1" >> commit.log

echo "Success" >> commit.log

exit 0
