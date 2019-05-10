xlist1 <-read.csv("/home/thiago/Dropbox/doutorado/redes_complexas/tp_final/grafo/trajetos.csv")
range(xlist1)

breaks = seq(0, 104, by=1)
medida.cut = cut(xlist1, breaks, right=FALSE)
medida.freq = table(medida.cut)
medida.cumfreq = cumsum(medida.freq)
medida.cumrelfreq = medida.cumfreq / 807
write(medida.cumrelfreq, "/home/thiago/Dropbox/doutorado/redes_complexas/tp_final/grafo/trajetos.txt", sep="\n")