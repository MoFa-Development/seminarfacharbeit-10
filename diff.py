#!/usr/bin/python3

import sys

a_file = open(sys.argv[1])
b_file = open(sys.argv[2])

a = a_file.read().split(" ")
b = b_file.read().split(" ")

a_file.close()
b_file.close()

lower_len = len(a) if len(a) < len(b) else len(b)

for i in range(lower_len):
    if a[i] != b[i]:
        print(f"------{i}------")
        print(sys.argv[1] + ": \t" + str(bytes(a[i], "utf-8")))
        print(sys.argv[2] + ": \t" + str(bytes(b[i], "utf-8")))
        print("-------------")

print(f"Wörter {sys.argv[1]}:\t{len(a)}")
print(f"Wörter {sys.argv[2]}:\t{len(b)}")