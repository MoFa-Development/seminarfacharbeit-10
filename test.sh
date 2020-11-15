#!/bin/bash

echo "----------------"
echo "compress"
echo "----------------"
./main.py cn $1 compressed.txt
echo "----------------"
echo "decompress"
echo "----------------"
./main.py dn compressed.txt restored.txt

echo "----------------"
echo "diff"
echo "----------------"
./diff.py $1 restored.txt
echo "----------------"
echo "chardiff"
echo "----------------"
./chardiff.py $1 restored.txt
echo "----------------"
echo "md5sum"
echo "----------------"
md5sum $1 restored.txt

rm restored.txt compressed.txt
