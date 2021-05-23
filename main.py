#!/usr/bin/python3

# region imports

import sys
import re
import datetime

# endregion

# region konstanten

LIST_REPLACEMENTS = ["[", "]", "'", ","]
REGEX_DELIMITERS = r"""\-|\+|=|:|>|<|\ |\
|\      |\.|¿|\?|,|¡|!|;|\(|\)|\[|\]|\{|\}|\$|\#|/|\&|" |'|»|«|\*"""
LIST_DELIMITERS = ['-', '+', '=', ':', '>', '<', ' ', '\n', '\t', '.', '¿', '?', ',',
                   '¡', '!', ';', '(', ')', '[', ']', '{', '}', '$', '#', '/', '&', '\"', '\'', '»', '«', '*']

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

additional_data: list = []

def replace(text: str, substr:str, repstr:str) -> str:
    matches = reversed(list(re.finditer(substr, text)))
    output = text

    for match in matches:
        index = match.start()
        if not index == 0 and not text[index-1] in LIST_DELIMITERS:
            continue
        if not index == len(text)-len(substr) and not text[index+len(substr)] in LIST_DELIMITERS:
            continue
        
        output = output[:index]+repstr+output[index+len(substr):]
        
    return output

words = []

def naive_compress(input_text: str):
    def add_word_to_list(word: str):    
        words.append(word)
        index = len(words)-1
            
        return index

    output = input_text

    #input_words = re.split('|'.join(map(re.escape, LIST_DELIMITERS)), input_text)
    input_words = re.split(REGEX_DELIMITERS, input_text)

    index = 0

    for i, word in enumerate(input_words):
        if word in words:
            continue
        if (word in input_words[i+1:] or word.isdigit()) and len(word) > 1: #Es werden nur wörter durch Indexe ersetzt, die mehr als ein Mal vorkommen, oder nur aus Zahlen bestehen, welche den Dekompremierungsalgorithmus zu Fehlern bringen würden.
            word_index = add_word_to_list(word)
            output = output[:index] + replace(output[index:], word, str(word_index))

            index += len(str(word_index))+1
        else:
            index += len(word)+1

    top_words = {}

    for word in words:
        top_words[word] = input_words.count(word)
    
    top_words = sorted(top_words.items(), key=lambda x: x[1], reverse=True)[:100]

    top_words_str = ""

    for word, amount in top_words:
        top_words_str += f"{word}:{amount},"

    top_words_str = top_words_str[:-1]

    additional_data.append("top_words: "+top_words_str)

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

        for datum in additional_data:
            print(datum)
        
        print("input_len: " + str(len(input_text)))
        print("output_len: " + str(len(output_text)))
        print("duplicate_words: " + str(len(words)))
        if len(input_text) == 0:
            print("char_rate: 0")
        else:
            print("char_rate: " + str(round(100 - len(output_text)/len(input_text)*100, 2)))

    else:
        usage()
        return()

if __name__ == "__main__":
    start = datetime.datetime.now().timestamp()
    main()
    end = datetime.datetime.now().timestamp()
    print(f"exec_time: {round(end-start, 6)}")
