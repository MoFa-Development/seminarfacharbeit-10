#!/usr/bin/python3

# main.py input.file output.file

import sys
import json
import re

#! Dieser Ansatz baut darauf, dass Worte wiederholt werden und gewinnt ausschließlich daraus Kompressionsvolumen. 
#! Finden keine oder kaum Wiederholungen statt, nimmt die Größe sogar zu.

def naive_compress(input_text : str):
    
    words = []

    def add_word_to_list(word : str):
        if not word in words:
            words.append(word)
            index = len(words)-1
        else:
            index = words.index(word)
        
        return index

    output = input_text

    input_words = re.split(" |\n", input_text)

    for word in input_words:
        if len(word) > 1:
            word_index = add_word_to_list(word)
            output = output.replace(word, str(word_index))

    return output


def decompress():
    print("decompression is not implemented yet")


def usage():
    print(f"Benutzung: {sys.argv[0]} [-compress / -decompress] <input file name> <output file name>")

def main():
    
    if len(sys.argv) == 4:
    
        action = 1 if sys.argv[1] == "-compress" else 2 if sys.argv[2] == "-decompress" else 3
        
        if action == 3:
            usage()
            return()

        input_file_name = sys.argv[2]
        output_file_name = sys.argv[3]

        try:
            input_file = open(input_file_name, "r")
        except Exception as e:
            print(e)
            print("Bitte geben Sie einen gültigen Input-Dateinamen an.")
            usage()
            return

        input_text = input_file.read()

        input_file.close()

        if action == 1:
            with open(output_file_name, "w") as output_file:
                output_file.write(naive_compress(input_text))

        if action == 2:
            decompress(input_text)

    else:
        usage()

if __name__ == "__main__":
    main()
