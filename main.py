#!/usr/bin/python3

# region imports

import sys
import re

# endregion

# region konstanten

LIST_REPLACEMENTS = ["[", "]", "'", ","]
LIST_DELIMITERS = ['-', '+', '=', ':', '>', '<', ' ', '\n', '\t', '.', '¿', '?', ',', '¡', '!', ';', '(', ')', '[', ']', '{', '}', '$', '#', '/', '&', '\"', '\'']

ACTIONS = {"help": 1, "cn": 2, "compress-naive": 2, "dn": 3, "decompress-naive": 3, "compress-naive-no-output": 4, "cnno": 4}
HELP = """
------------ Hilfe -------------

Syntax: main.py <Aktion> <Eingabedatei> <Ausgabedatei>

Wenn keine Ausgabedatei angegeben wird, wird die Ausgabe über den stdout-Kanal ausgegeben. 

----------- Aktionen -----------

help                            -   Ausgabe dieser Hilfe

cn / compress-naive             -   Naive Kompression
dn / decompress-naive           -   Umkehrung der naiven Kompression
cnno / compress-naive-no-output -   Naive Kompression ohne ausgabe des Textes
""".replace("main.py", sys.argv[0])

# endregion

#! Dieser Ansatz baut darauf, dass Worte wiederholt werden und gewinnt
#! ausschließlich daraus Kompressionsvolumen. 

def find(string:str, substring:str):
    indexes = []

    beginning = substring[0]
    length = len(substring)

    for i, char in enumerate(string):
        if char == beginning:
            if string[i:i+length] == substring:
                indexes.append(i)

    indexes.sort(reverse=True)

    return indexes

def replace(text: str, original:str, replace_str:str) -> str:
    indexes = find(text, original)
    output = text

    for index in indexes:
        if not index == 0 and not text[index-1] in LIST_DELIMITERS:
            continue
        if not index == len(text)-len(original) and not text[index+len(original)] in LIST_DELIMITERS:
            continue
        
        output = output[:index]+replace_str+output[index+len(original):]
        
    return output


words = []

def naive_compress(input_text: str):
    def add_word_to_list(word: str):    
        words.append(word)
        index = len(words)-1
            
        return index

    output = input_text

    input_words = re.split('|'.join(map(re.escape, LIST_DELIMITERS)), input_text)

    index = 0

    for word in input_words:
        if word in words:
            continue
        if (input_words.count(word) >= 2 or word.isdigit()) and len(word) > 0: #Es werden nur wörter durch Indexe ersetzt, die mehr als ein Mal vorkommen, oder nur aus Zahlen bestehen, welche den Dekompremierungsalgorithmus zu Fehlern bringen würden.
            word_index = add_word_to_list(word)
            output = output[:index] + replace(output[index:], word, str(word_index))

            index += len(str(word_index))+1
        else:
            index += len(word)+1

    words_str = " ".join(words)

    output += " \n" + words_str

    return output

def naive_decompress(input_text : str):
    
    words = input_text.split('\n')[-1].split(" ")
    
    output = "\n".join(input_text.split('\n')[0:-1])
    
    for i in range(len(words)):
        output = replace(output, str(i), words[i])

    output = output[:-1]

    return output


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
        else:
            write_to_file = False
            output_file_name = ""

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

        if action == 2 or action == 4:
            output_text = naive_compress(input_text)
        elif action == 3:
            output_text = naive_decompress(input_text)
        else:
            output_text = ""
        
        if write_to_file:
            with open(output_file_name, "w") as output_file:
                output_file.write(output_text)
        elif action != 4:
            sys.stdout.write(output_text)

        print("-----")
        print("")
        print("Input len:\t" + str(len(input_text)))
        print("Output len:\t" + str(len(output_text)))
        print("Duplikatswörter:\t" + str(len(words)))
        print("\033[1mZeichen:\t" + str(round(100 - len(output_text)/len(input_text)*100, 2)) + "%\033[0m")
        print("")

    else:
        usage()
        return()

if __name__ == "__main__":
    main()
