#!/bin/bash

input=$1
compressed="$1.compressed"
restored="$1.restored"

echo "----------------"
echo "compress"
echo "----------------"
./main.py cn $1 $compressed
echo "----------------"
echo "decompress"
echo "----------------"
./main.py dn $compressed $restored

echo "----------------"
echo "diff"
echo "----------------"
./diff.py $1 $restored
echo "----------------"
echo "chardiff"
echo "----------------"
./chardiff.py $1 $restored
echo "----------------"
echo "md5sum"
echo "----------------"
md5sum $1 $restored
