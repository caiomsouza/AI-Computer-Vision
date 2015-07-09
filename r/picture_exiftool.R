# http://timelyportfolio.github.io/rCharts_catcorrjs/exif/

#do a combination
info <- system('exiftool -T -r -ISO -ShutterSpeed -CameraTemperature -DateTimeOriginal "/Users/caiomsouza/Downloads/IMG_0969.JPG"',inter=TRUE)
img.df <- read.delim2(
  textConnection(info),
  stringsAsFactors = FALSE,
  header = FALSE,
  col.names = c("ISO", "ShutterSpeed", "CameraTemp", "Date")
)

head(img.df)

#get just date
img.df[,4] <- as.Date(#as.POSIXct(
  paste0(
    gsub(x=substr(img.df[,4],1,10),pattern=":",replacement="-"),
    substr(img.df[,4],11,19)
  )
)

head(img.df)

img.df$id = 1:NROW(img.df)
#install.packages("vcdExtra")
require(vcdExtra)
x11(width = 20, height = 12)
mosaic(structable(img.df[,c(1,2)]))


assoc(img.df[,c(1,2)],shade=T)

#install.packages("rCharts")
require(rCharts)

#remove iso speeds that are not numeric
#manual for now
img.df <- img.df[-(which(is.na(as.numeric(img.df[,1])))),]
img.df$Date <- format(img.df$Date)
catCorrPlot <- function(questions, responses){
  require(rCharts)
  #responses = read.csv(responses_doc)
  responses = toJSONArray(setNames(
    responses[,-1], 1:(NCOL(responses) - 1)
  ), json = F)
  #questions = read.csv(questions_doc, stringsAsFactors = F)
  questions = lapply(1:NROW(questions), function(i){
    qi = as.list(questions[i,])
    qi$choices = strsplit(qi$choices, ";")[[1]]
    qi$number = i
    qi
  })
  questions = toJSONArray(questions, json = F)
  r1 <- rCharts$new()
  r1$setLib('http://timelyportfolio.github.io/howitworks/catcorrjs/catcorrjs')
  r1$set(questions = questions, responses = responses)
  r1
}
responses <- img.df[,c(4,4,1,2)]
questions <- do.call(rbind,lapply(1:2,function(x){
  choices <- unique(img.df[,x])
  choices <- choices[order(unlist(lapply(choices,function(x){
    as.numeric(eval(parse(text=x)))
  })))]
  return(data.frame(
    "outcome",
    colnames(img.df)[x],
    capture.output(cat(choices,sep=";")),
    stringsAsFactors = F
  )
  )
}
))
colnames(questions) <- c("type","text","choices")
questions <- rbind(questions,c("demographic","Date", capture.output(cat(unique(img.df[,4]),sep=";"))))
questions <- questions[c(3,1,2),]   
r1 <- catCorrPlot(questions, responses)
r1$show("inline")

