
setwd("~/git/github.com/instagram-api")

picture_metadata <- system('exiftool "/Users/caiomsouza/Downloads/IMG_0969.JPG"',inter=TRUE)

head(picture_metadata)


