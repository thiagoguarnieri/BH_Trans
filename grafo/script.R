library("igraph")

xlist1 <-read.csv("/home/thiago/Dropbox/doutorado/redes_complexas/tp_final/grafo/grafos/grafo_3.csv")
rede1 <- graph.data.frame(xlist1, directed=TRUE)
grau <- degree(rede1, v=V(rede1), mode = "out", loops = FALSE, normalized = FALSE)

################################################################
#grafo
l <- layout.lgl(rede1)
l <- layout.norm(l, ymin=-1, ymax=1, xmin=-4, xmax=4)
jpeg('/home/thiago/Dropbox/doutorado/redes_complexas/tp_final/grafo/grafos/grafo_1.jpg')
plot.igraph(rede1, vertex.size=2,vertex.label = NA, edge.arrow.size=.1)
dev.off()

################################################################
#componentes
comp <- components(rede1, mode = "strong")

#closeness
close <- closeness(rede1, vids = 1, mode = "in", weights = NULL, normalized = TRUE)

#diameter
farthest_vertices(rede1, directed = TRUE, unconnected = TRUE, weights = NULL)
get_diameter(rede1, directed = TRUE, unconnected = TRUE, weights = NULL)

#path length
average.path.length(rede1, directed=TRUE, unconnected=TRUE)

#shortest path
shortest.paths(rede1, v=V(rede1), c(65),mode = "out", weights = NULL, algorithm = "automatic")

#histograma dos menores caminhos
hist <- path.length.hist(rede1, directed=TRUE)
write(hist$res, "/home/thiago/Dropbox/doutorado/redes_complexas/tp_final/grafo/short_paths/pdf1", sep="\n")

#todos os menores caminhos
for (i in 1397:3777){
  caminhos <- get.shortest.paths(rede1, i, mode = "out", weights = NULL, output= "vpath", predecessors = FALSE, inbound.edges = FALSE)
  lapply(caminhos$vpath, write, "/home/thiago/Dropbox/doutorado/redes_complexas/tp_final/grafo/all_paths2.txt", append=TRUE, ncolumns=80)
  caminhos <- NULL
}

#grau
grau <- degree_distribution(rede1, cumulative = TRUE, mode = "out")
write(hist$res, "/home/thiago/Dropbox/doutorado/redes_complexas/tp_final/grafo/short_paths/pdf1", sep="\n")
#lapply(grau, write, "/home/thiago/Dropbox/doutorado/redes_complexas/tp_final/grafo/grau_out.txt", append=TRUE, ncolumns=80)

betw = betweenness(rede1, v=V(rede1), directed = TRUE, weights = NULL, nobigint = TRUE, normalized = TRUE)

##################################################################
xlist1 <-read.csv("/home/thiago/Dropbox/doutorado/redes_complexas/tp_final/grafo/grafos/grafo_9.csv")
rede1 <- graph.data.frame(xlist1, directed=TRUE)
grau <- degree(rede1, v=V(rede1), mode = "out", loops = FALSE, normalized = FALSE)

vecnum <- grau
breaks = seq(range(vecnum)[1], range(vecnum)[2], 1)
medida.cut = cut(vecnum, breaks, right=FALSE)
medida.freq = table(medida.cut)
medida.cumfreq = cumsum(medida.freq)
medida.relfreq = medida.freq / sum(medida.freq)
medida.cumrelfreq = medida.cumfreq / sum(medida.freq)
write(range(vecnum), "/home/thiago/Dropbox/doutorado/redes_complexas/tp_final/grafo/grau/cdf9.txt", sep="#", append = TRUE)
write(medida.cumrelfreq, "/home/thiago/Dropbox/doutorado/redes_complexas/tp_final/grafo/grau/cdf9.txt", sep="\n", append = TRUE)




