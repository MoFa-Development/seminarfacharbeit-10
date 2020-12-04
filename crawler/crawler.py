#!/usr/bin/python3
from lxml import html
import requests
import os
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

        i = 1
        while source.xpath(f"/html/body/dl/dd[{i}]/a"):
            alle_werke.append(source.xpath(f"/html/body/dl/dd[{i}]/a/@href")[0])
            i += 1

        for werk_link in alle_werke:
            werk_page = requests.get("https://projekt-gutenberg.org"+werk_link[5:])
            werk_source = html.fromstring(werk_page.content)

            filename = "/tmp/projekt-gutenberg"+"/".join(werk_link[5:].split("/")[:-1])+".txt"
            print(filename)

            os.makedirs(os.path.dirname(filename), exist_ok=True)
            f = open(filename, "w")

            #/html/body/a[3]

            #werk_source.xpath("/html/body/a[3]")[0].text
            author = werk_source.xpath("/html/body/h5[1]")[0].text

            while werk_source.xpath("/html/body/a[3]/@href") and "weiter" in werk_source.xpath("/html/body/a[3]")[0].text:
                newlink = "https://projekt-gutenberg.org" + "/".join(werk_link[5:].split("/")[:-1]) + "/" + werk_source.xpath("/html/body/a[3]/@href")[0]
                werk_page = requests.get(newlink)
                werk_source = html.fromstring(werk_page.content)
                
                for thing in werk_source.xpath("/html/body/*"):
                    if thing.tag == "p" or thing.tag == "h3":
                        if thing.text:
                            f.write(thing.text+"\n\n")
            
            f.close()

            process = subprocess.run(["python3", os.path.abspath(
                os.path.dirname(__file__))+"/../main.py", "cn", filename, "/dev/null"], capture_output=True)
            
            print(str(process.stdout, "utf-8"))

            data_raw = str(process.stdout, "utf-8").split("\n")
            data = {}
            
            for line in data_raw:
                if ": " in line:
                    split = line.split(": ")
                    data[split[0]] = split[1]

            sql = "INSERT INTO articles (topTenWords, inputLen, outputLen, duplicateWords, charRate, author, execTime) VALUES (%s, %s, %s, %s, %s, %s, %s)"
            val = (data["top_words"], int(data["input_len"]), int(data["output_len"]), int(data["duplicate_words"]), float(data["char_rate"]), author, float(data["exec_time"]))
            db_cursor.execute(sql, val)
            db.commit()

            # Es ist 00:17 Uhr, ich habe ungefähr 3 Stunden gearbeitet und fange an in die Leere starrend "Don't worry be happy" zu summen, nachdem ich eine nach der anderen kryptischen Fehlermeldung gefixt habe. 




crawler("https://www.projekt-gutenberg.org/info/texte/allworka.html")
