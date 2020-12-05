#!/usr/bin/python3
from lxml import html
import requests
import os
import sys
import mysql.connector
import subprocess

db = mysql.connector.connect(
            host="seminarfach.zapto.org",
            user=os.getenv('SF_DB_NAME'),
            password=os.getenv('SF_DB_PASSWORD'),
            database="seminarfach"
            )

db_cursor = db.cursor()

class crawler:
    def __init__(self, link):
        page = requests.get(link)

        source = html.fromstring(page.content)

        alle_werke = []

        tmp_author = ""

        for item in source.xpath("/html/body/dl/*"):
            if item.tag == "dt":
                tmp_author = item.text
            elif item.tag == "dd":
                children = item.getchildren()
                if len(children) >= 2 and "href" in children[0].attrib and children[1].getchildren():
                    alle_werke.append((children[0].attrib["href"], children[0].text, tmp_author, children[1].getchildren()[0].text))

        print(f"Anzahl Werke: {len(alle_werke)}")
        if len(sys.argv) == 2:
            print("Untere Grenze: "+sys.argv[1])
            alle_werke = alle_werke[int(sys.argv[1]):]

        self.i = 0
        
        while self.i < len(alle_werke):

            db_cursor.execute("SELECT value FROM temp WHERE name = 'clustering';")
            self.i = db_cursor.fetchone()[0]+1
            db_cursor.execute(f"UPDATE temp SET value = {self.i} WHERE name = 'clustering';")
            db.commit()

            print("index: "+str(self.i))
            
            werk_info = alle_werke[self.i]

            werk_link = werk_info[0]
            title = werk_info[1]
            author = werk_info[2]
            genre = werk_info[3]

            werk_page = requests.get("https://projekt-gutenberg.org"+werk_link[5:])
            werk_source = html.fromstring(werk_page.content)
            
            print("https://projekt-gutenberg.org"+werk_link[5:])
            filename = "/tmp/projekt-gutenberg"+"/".join(werk_link[5:].split("/")[:-1])+".txt"
            print("filename='"+filename+"'")

            os.makedirs(os.path.dirname(filename), exist_ok=True)
            f = open(filename, "w")

            #/html/body/a[3]

            #werk_source.xpath("/html/body/a[3]")[0].text
            
            for thing in werk_source.xpath("/html/body/*"):
                if thing.tag == "p" or thing.tag == "h3" or thing.tag == "h2":
                    if thing.text:
                        if "<img src=" in thing.text:
                            continue
                        f.write(thing.text.replace("<br>", "\n")+"\n\n")

            while werk_source.xpath("/html/body/a[3]/@href") and "weiter" in werk_source.xpath("/html/body/a[3]")[0].text:
                newlink = "https://projekt-gutenberg.org" + "/".join(werk_link[5:].split("/")[:-1]) + "/" + werk_source.xpath("/html/body/a[3]/@href")[0]
                print(newlink)
                werk_page = requests.get(newlink)
                werk_source = html.fromstring(werk_page.content)
                
                for thing in werk_source.xpath("/html/body/*"):
                    if thing.tag == "p" or thing.tag == "h3" or thing.tag == "h2":
                        if thing.text:
                            if "<img src=" in thing.text:
                                continue
                            f.write(thing.text.replace("<br>", "\n")+"\n\n")
            
            f.close()

            process = subprocess.run(["python3", os.path.abspath(os.path.dirname(__file__))+"/../main.py", "cn", filename, "/dev/null"], capture_output=True)
            
            print(str(process.stdout, "utf-8"))

            data_raw = str(process.stdout, "utf-8").split("\n")
            data = {}
            
            for line in data_raw:
                if ": " in line:
                    split = line.split(": ")
                    data[split[0]] = split[1]

            sql = "INSERT INTO articles (topWords, inputLen, outputLen, duplicateWords, charRate, author, execTime, title, genre, url) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
            val = (data["top_words"], int(data["input_len"]), int(data["output_len"]), int(
                data["duplicate_words"]), float(data["char_rate"]), author, float(data["exec_time"]), title, genre, "https://projekt-gutenberg.org"+werk_link[5:])
            db_cursor.execute(sql, val)
            db.commit()

            os.remove(filename)



crawler("https://www.projekt-gutenberg.org/info/texte/allworka.html")
