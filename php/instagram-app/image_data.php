<html>
  <head>
    <style>
    #container {
      margin: 0 auto;
    }
    #info {
      float: left;
      width: 300px;
      padding-right: 20px;
    }
    #image {
      float: left;
      width: 320px;
      padding-right: 20px;
    }
    #comments {
      clear: both;
    }
    .item {
      float:none;
      clear:both;
      margin-top:1em;
    }
    .profile {
      float:left;
      margin-right:1em;
      padding-bottom: 10px;
      height: 48px;
      width: 48px;
    }
    </style>
  </head>
  <body>
    <h1>Instagram Image Detail</h1>
    <?php
    // load Zend classes
    require_once 'Zend/Loader.php';
    Zend_Loader::loadClass('Zend_Http_Client');

    // define consumer key and secret
    // available from Instagram API console

    //$CLIENT_ID = 'YOUR-CLIENT-ID';
    //$CLIENT_SECRET = 'YOUR-CLIENT-SECRET';

    require_once 'client_credential.php';


    try {
      // define image id
      $image = '338314508721867526';

// https://scontent.cdninstagram.com/hphotos-xat1/t51.2885-15/e15/10919238_355898587944405_259469863_n.jpg

// https://scontent.cdninstagram.com/hphotos-xat1/t51.2885-15/s150x150/e15/10919238_355898587944405_259469863_n.jpg

      // I did not find how to know the image id by a image url

      // image url
      // https://scontent.cdninstagram.com/hphotos-xfa1/t51.2885-15/s640x640/e35/sh0.08/11427260_913609078699176_625661046_n.jpg

      //  $image = '913609078699176625';


      // initialize client
      $client = new Zend_Http_Client('https://api.instagram.com/v1/media/' . $image);
      $client->setParameterGet('client_id', $CLIENT_ID);

      // get image metadata
      $response = $client->request();
      $result = json_decode($response->getBody());

      // display image data
    ?>
      <div id="container">
        <div id="info">
          <h2>Meta</h2>
          <strong>Date: </strong>
          <?php echo date('d M Y h:i:s', $result->data->created_time); ?>
          <br/>
          <strong>Creator: </strong>
          <?php echo $result->data->user->username; ?>
          (<?php echo !empty($result->data->user->full_name) ?
            $result->data->user->full_name : 'Not specified'; ?>)
          <br/>
          <strong>Location: </strong>
          <?php echo !is_null($result->data->location) ?
          $result->data->location->latitude . ',' .
            $result->data->location->longitude : 'Not specified'; ?>
          <br/>
          <strong>Filter: </strong>
          <?php echo $result->data->filter; ?>
          <br/>
          <strong>Comments: </strong>
          <?php echo $result->data->comments->count; ?>
          <br/>
          <strong>Likes: </strong>
          <?php echo $result->data->likes->count; ?>
          <br/>
          <strong>Resolution: </strong>
          <a href="<?php echo $result->data->images
            ->standard_resolution->url; ?>">Standard</a> |
          <a href="<?php echo $result->data->images
            ->thumbnail->url; ?>">Thumbnail</a>
          <br/>
          <strong>Tags: </strong>
          <?php echo implode(',', $result->data->tags); ?>
          <br/>
        </div>
        <div id="image">
          <h2>Image</h2>
          <img src="<?php echo $result->data->images
            ->low_resolution->url; ?>" /></a>
        </div>
        <div id="comments">
          <?php if ($result->data->comments->count > 0): ?>
          <h2>Comments</h2>
          <ul>
            <?php foreach ($result->data->comments->data as $c): ?>
              <div class="item"><img src="<?php echo $c
                ->from->profile_picture; ?>" class="profile" />
              <?php echo $c->text; ?> <br/>
              By <em> <?php echo $c->from->username; ?></em>
              on <?php echo date('d M Y h:i:s', $c->created_time); ?>
              </div>

              </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
        </div>
      </div>
    <?php
    } catch (Exception $e) {
      echo 'ERROR: ' . $e->getMessage() . print_r($client);
      exit;
    }
    ?>
  </body>
</html>
