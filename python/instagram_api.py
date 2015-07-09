# -*- coding: utf-8 -*-
"""
Created on Thu Jul  9 15:02:41 2015

@author: Caio Moreno de Souza
@twitter: twitter.com/caiomsouza
"""


# Import the instagram api
# more about the api https://github.com/Instagram/python-instagram
# run the command pip install python-instagram on your terminal to install the api
from instagram.client import InstagramAPI

# Go to https://instagram.com to get your client_secret and client_id
my_client_secret = 'YYYYYYYYYYYYYY'
my_client_id = 'XXXXXXXXXXXXXXXXXXX'

# Call the API
api = InstagramAPI(client_id=my_client_id, client_secret=my_client_secret)


# Get popular media from Instagram

print "Example of Getting the popular media from Instagram"

popular_media = api.media_popular(count=10)
for media in popular_media:
    print media.images['standard_resolution'].url

    
    
    

print "Let's try something more cool with the Instagram API"    


print "In this example we can search media by latitude and longitude"    

# Madrid
media_search = api.media_search(q="paradise places", count=5, lat='40.3985540', lng='-3.6216730')    

# Cotia, SP
#media_search = api.media_search(q="cars", count=5, lat='-23.6026680', lng='-46.9194690')

# Munich, Germany
#media_search = api.media_search(q="#big data", count=5, lat='48.1351250', lng='11.5819810')

# Moscow, Russia
#media_search = api.media_search(q="#big data", count=5, lat='55.7558260', lng='37.6173000')

for medias in media_search:
    print medias.images['standard_resolution'].url    

    
    
print "Now let's work with more cities - We will create a Location Matrix" 
  
# website for geolocations   
# http://mygeoposition.com

print "Use the website http://mygeoposition.com to know your latitude and longitude." 

# Our Location Matrix
locations = [['Parque del Retiro, Calle Princesa, 27, 28008 Madrid, Madrid, Spain', '40.4273640','-3.7141800'], ['Rio de Janeiro - State of Rio de Janeiro, Brazil', '-22.9068470','-43.1728960'], ['Ibiza, Balearic Islands, Spain', '38.9067340','1.4205980'], ['Florianópolis - State of Santa Catarina, Brazil', '-27.5953780','-48.5480500'], ['Madrid', '40.3985540','-3.6216730'], ['Cotia','-23.6026680','-46.9194690'], ['Munich','48.1351250','11.5819810'], ['Moscow','55.7558260','37.6173000']]




# print the cities and locations
for row in locations:
   print ' '.join(row)


# Define the search phrase but I think it does not work because the pictures are not related sometimes.
search_phrase = "paradise beaches"

# Define the Number of pictures
count_number = 5


# This is just a counter.
photo_number = 0

# This loop will print all photos by cities
for row in locations:

    media_search = api.media_search(q=search_phrase, count=count_number, lat=row[1], lng=row[2])

    for medias in media_search:
      
      photo_number = photo_number + 1
      
      print "City: "  
      print row[0]
      
      print "Photo number: "
      print photo_number 
      
      print medias.images['standard_resolution'].url    



print "That's it. Enjoy it"    