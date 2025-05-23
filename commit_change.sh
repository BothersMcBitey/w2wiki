#!/bin/bash

# arg $0 is script name, same as python

if [ $# -eq 0 ]
  then
    echo "not enough arguments, missing commit message"
    exit 1
fi

git add ./pages/*

git commit -m "$1"
