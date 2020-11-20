from lxml import html
import requests

prefix = "https://www.liederkiste.com/"

class crawler:
    def __init__(self, link):
        page = requests.get(link)

        source = html.fromstring(page.content)

        for paragraph in source.xpath("/html/body/div/div/div[1]/div[1]"):
            for p in paragraph.cssselect("p"):
                print(p.text_content())

        for title in source.xpath("/html/body/div/div/h2"):
            if not "Volkslieder" in title.text_content():
                return
            
        links = source.xpath('//a/@href')
        for l in links:
            crawler(prefix + l)
            print(l)

crawler("https://www.liederkiste.com/index.php?c=volkslieder&l=de")