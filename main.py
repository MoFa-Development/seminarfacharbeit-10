#!/usr/bin/python3

# main.py input.file output.file

import sys

def magie(input_text : str):
    output = ""
    return output

def usage():
    print(f"Benutzung: {sys.argv[0]} <input file name> <output file name>")

def main():
    
    if len(sys.argv) == 3:
    
        input_file_name = sys.argv[1]
        output_file_name = sys.argv[2]

        try:
            input_file = open(input_file_name, "r")
        except Exception as e:
            print(e)
            print("Bitte geben sie einen gültigen Input-Dateinamen an.")
            usage()
            return

        input_text = input_file.read()

        input_file.close()

        with open(output_file_name, "w") as output_file:
            output_file.write(magie(input_text))
    else:
        usage()

if __name__ == "__main__":
    main()
