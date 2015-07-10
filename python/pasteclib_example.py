#!/usr/bin/python3
# https://github.com/Visu4link/pastec/blob/dist-ver/python/PastecLib.py
# See Benchmarking Pastec with the Stanford Mobile Visual Search Data Set http://pastec.io/blog

import PastecLib

DATASET_PATH = "/path/to/dvd_covers"

PATH_REFERENCES = DATASET_PATH + "/Reference/"

PATH_QUERIES = DATASET_PATH + "/Canon/"
#PATH_QUERIES = DATASET_PATH + "/Droid/"
#PATH_QUERIES = DATASET_PATH + "/E63/"
#PATH_QUERIES = DATASET_PATH + "/Palm/"

c = PastecLib.PastecConnection()

# Index the reference files.
for i in range(1, 101):
    c.indexImageFile(i, PATH_REFERENCES + "%03d.jpg" %i)

goodMatches = 0

# Query the index.
for i in range(1, 101):
    res = c.imageQueryFile(PATH_QUERIES + "%03d.jpg" %i)
    if res != [] and res[0] == i:
        goodMatches += 1
    else:
        print("Image n°%d not matched." % i)

print("Number of good matches: %d" % goodMatches)
