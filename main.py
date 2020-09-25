#!/usr/bin/python3

# region imports

import sys
import json
import re

# endregion

# region konstanten

ACTIONS = {"help": 1, "cn": 2, "compress-naive": 2, "dn": 3, "decompress-naive": 3}
HELP = """
------------ Hilfe -------------

Syntax: main.py <Aktion> <Eingabedatei> <Ausgabedatei>

Wenn keine Ausgabedatei angegeben wird, wird die Ausgabe über den stdout-Kanal ausgegeben. 

----------- Aktionen -----------

help                     -   Ausgabe dieser Hilfe

cn / compress-naive      -   Naive Kompression
dn / decompress-naive    -   Umkehrung der naiven Kompression
""".replace("main.py", sys.argv[0])

# endregion

#! Dieser Ansatz baut darauf, dass Worte wiederholt werden und gewinnt
#! ausschließlich daraus Kompressionsvolumen. 
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

def naive_decompress(input_text : str):
    print("Naive Dekomprimierung ist noch nicht implementiert")


def usage():
    print(f"Benutzung: {sys.argv[0]} <Aktion> <Eingabedatei> <Ausgabedatei>")
    print(f"Weitere Hilfe: {sys.argv[0]} help")


def main():

    if len(sys.argv) > 1 and sys.argv[1] in ACTIONS:
        action = ACTIONS[sys.argv[1]]
    else:
        usage()
        return()
    
    if action == 1:
        print(HELP)
        return

    if len(sys.argv) >= 3:

        # region input file stuff

        input_file_name = sys.argv[2]
        
        if len(sys.argv) >= 4:
            write_to_file = True
            output_file_name = sys.argv[3]

        try:
            input_file = open(input_file_name, "r")
        except Exception as e:
            print(e)
            print("Bitte geben Sie einen gültigen Eingabedateinamen an.")
            usage()
            return()

        input_text = input_file.read()
        input_file.close()

        # endregion

        if action == 2:
            output_text = naive_compress(input_text)
        elif action == 3:
            output_text = naive_decompress(input_text)
        
        if write_to_file:
            with open(output_file_name, "w") as output_file:
                output_file.write(output_text)
        else:
            sys.stdout.write(output_text)

    else:
        usage()
        return()

if __name__ == "__main__":
    main()
