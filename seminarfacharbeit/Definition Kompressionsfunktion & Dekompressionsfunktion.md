Es seien die Kompressionsfunktion $f$ und die Dekompressionsfunktion $g$.

Die Kompressionsfunktion $f(m)$ bilde von der Menge $M$ aller Nachrichten $m$ auf die Menge $N$ aller komprimierten Nachrichten $n = f(m)$ ab. Es gelte: $f : M \rarr N$.

Die Dekompressionsfunktion $g(n)$ sei die Umkehrfunktion von $f(m)$. Es gelte: $g : N \rarr M$

Beide Abbildungen $f : M \rarr N$ und $g : N \rarr M$ seien bijektiv. Es gebe also für jede Nachricht $m \epsilon M$ nur eine einzige eindeutig auf die Ursprungsnachricht $m \epsilon M$ zurückführbare komprimierte Nachricht $n \epsilon N$ und andersherum. 

Für eine erfolgreiche Kompression sollte des Weiteren für jede Nachricht $m \epsilon M$ gelten: $len(m) \ge len(f(m))$ wobei $len$ die Länge der Nachricht in Buchstaben ausdrückt. Dies folgt daraus, dass eine Kompression zum Ziel hat, die gleiche Information in weniger Speicherplatz zu enthalten.