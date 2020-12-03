#!/usr/bin/python3
from lxml import html
import requests
import os

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

            filename = "/".join(werk_link[5:].split("/")[:-1])[1:]+".txt"
            print(filename)

            os.makedirs(os.path.dirname(filename), exist_ok=True)
            f = open(filename, "w")

            #/html/body/a[3]

            #werk_source.xpath("/html/body/a[3]")[0].text

            while werk_source.xpath("/html/body/a[3]/@href") and "weiter" in werk_source.xpath("/html/body/a[3]")[0].text:
                newlink = "https://projekt-gutenberg.org" + "/".join(werk_link[5:].split("/")[:-1]) + "/" + werk_source.xpath("/html/body/a[3]/@href")[0]
                werk_page = requests.get(newlink)
                werk_source = html.fromstring(werk_page.content)
                
                for thing in werk_source.xpath("/html/body/*"):
                    if thing.tag == "p" or thing.tag == "h3":
                        if thing.text:
                            f.write(thing.text+"\n\n")
        

            f.close()




crawler("https://www.projekt-gutenberg.org/info/texte/allworka.html")
