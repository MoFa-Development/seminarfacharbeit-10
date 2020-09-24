#!/usr/bin/python3

# main.py input.file output.file

import os

def magie(input_text : str):
    return output




if __name__ == "__main__":

    if len(os.argv) == 3:
    
        input_file_name = os.argv[1]
        output_file_name = os.argv[2]

        try:
            input_file = open(input_file_name, "r")
        except Exception e:
            print(e)

        input_text = input_file.read()

        input_file.close()

        with open(output_file_name, "w") as output_file:
            output_file.write(magie(input_text))
        

