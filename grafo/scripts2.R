library("igraph")

xlist1 <-read.csv("/home/thiago/Dropbox/doutorado/redes_complexas/tp_final/grafo/grafos/grafo_1.csv")
rede1 <- graph.data.frame(xlist1, directed=TRUE, vertices=NULL)

#tamanho
ecount(rede1)

############################################################
#distância até o centro da cidade
paths<- shortest.paths(rede1, v=V(rede1), c("74"),mode = "in", weights = NULL, algorithm = "automatic")

vecnum <- paths
breaks = seq(range(vecnum, finite = TRUE)[1], range(vecnum, finite = TRUE)[2], 1)
medida.cut = cut(vecnum, breaks, right=FALSE)
medida.freq = table(medida.cut)
medida.cumfreq = cumsum(medida.freq)
medida.relfreq = medida.freq / sum(medida.freq)
medida.cumrelfreq = medida.cumfreq / sum(medida.freq)
write(medida.cumrelfreq, "/home/thiago/Dropbox/doutorado/redes_complexas/tp_final/grafo/dist_centro/short_path2.txt", sep="\n", append = TRUE)
############################################################

#average shortest path
xlist1 <-read.csv("/home/thiago/Dropbox/doutorado/redes_complexas/tp_final/grafo/grafos/grafo_9.csv")
rede1 <- graph.data.frame(xlist1, directed=TRUE, vertices=NULL)

apath = average.path.length(rede1, directed=TRUE, unconnected=TRUE)
write(apath, "/home/thiago/Dropbox/doutorado/redes_complexas/tp_final/grafo/dist_centro/average.txt", sep="\n", append = TRUE)

#############################################################
xlist1 <-read.csv("/home/thiago/Dropbox/doutorado/redes_complexas/tp_final/grafo/grafos/grafo_9.csv")
rede1 <- graph.data.frame(xlist1, directed=TRUE, vertices=NULL)
betw = betweenness(rede1, v=V(rede1), directed = TRUE, weights = NULL, nobigint = TRUE, normalized = TRUE)

vecnum <- betw
breaks = seq(range(vecnum, finite = TRUE)[1], range(vecnum, finite = TRUE)[2], 0.01)
medida.cut = cut(vecnum, breaks, right=FALSE)
medida.freq = table(medida.cut)
medida.cumfreq = cumsum(medida.freq)
medida.relfreq = medida.freq / sum(medida.freq)
medida.cumrelfreq = medida.cumfreq / sum(medida.freq)
write(medida.cumrelfreq, "/home/thiago/Dropbox/doutorado/redes_complexas/tp_final/grafo/betwe/9.txt", sep="\n", append = TRUE)

############################################################
xlist1 <-read.csv("/home/thiago/Dropbox/doutorado/redes_complexas/tp_final/grafo/grafos/grafo_1.csv")
rede1 <- graph.data.frame(xlist1, directed=TRUE, vertices=NULL)
trans <- transitivity(rede1, type = c("local"), vids = NULL,weights = NULL, isolates = c("zero"))
graph.density(rede1, loops=FALSE)

vecnum <- trans
breaks = seq(range(vecnum, finite = TRUE)[1], range(vecnum, finite = TRUE)[2], 0.01)
medida.cut = cut(vecnum, breaks, right=FALSE)
medida.freq = table(medida.cut)
medida.cumfreq = cumsum(medida.freq)
medida.relfreq = medida.freq / sum(medida.freq)
medida.cumrelfreq = medida.cumfreq / sum(medida.freq)
write(medida.cumrelfreq, "/home/thiago/Dropbox/doutorado/redes_complexas/tp_final/grafo/abunda/9.txt", sep="\n", append = TRUE)
