<html>
  <head>
    <style>
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
    </style>
  </head>
  <body>
    <h1>Instagram User Profile</h1>
    <?php
    if (!isset($_POST['submit'])) {
    ?>
    <form method="post"
      action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
      Search for user:
      <input type="text" name="u" />
      <input type="submit" name="submit" value="Search" />
    </form>
    <?php
    } else {
    ?>
    <?php
      require_once 'Zend/Loader.php';
      Zend_Loader::loadClass('Zend_Http_Client');

      // define consumer key and secret
      // available from Instagram API console

      //$CLIENT_ID = 'YOUR-CLIENT-ID';
      //$CLIENT_SECRET = 'YOUR-CLIENT-SECRET';

      require_once 'client_credential.php';


      try {
        // initialize client
        $client = new Zend_Http_Client('https://api.instagram.com/v1/users/search');
        $client->setParameterGet('client_id', $CLIENT_ID);
        $client->setParameterGet('q', $_POST['u']);
        $client->setParameterGet('count', '1');

        // search for matching users
        $response = $client->request();
        $result = json_decode($response->getBody());

        // get user id from search results
        if (count($result->data) != 1) {
          echo 'No matches found';
          exit;
        } else {
          $id = $result->data[0]->id;
        }

        // get user's profile information
        $client->setUri('https://api.instagram.com/v1/users/' . $id);
        $client->setParameterGet('client_id', $CLIENT_ID);
        $response = $client->request();
        $result = json_decode($response->getBody());
        $data = $result->data;
    ?>
        <div id="image">
          <h2>Image</h2>
          <img src="<?php echo $result->data->profile_picture; ?>"
            /></a>
        </div>
        <div id="info">
          <h2>Meta</h2>
          <strong>Username: </strong>
          <?php echo $result->data->username; ?>
          <br/>
          <strong>Instagram id: </strong>
          <?php echo $id; ?>
          <br/>
          <strong>Full name: </strong>
          <?php echo !empty($result->data->full_name) ?
            $result->data->full_name : 'Not specified'; ?>
          <br/>
          <strong>Bio: </strong>
          <?php echo !empty($result->data->bio) ?
            $result->data->bio : 'Not specified'; ?>
          <br/>
          <strong>Website: </strong>
          <?php echo !empty($result->data->website) ?
            $result->data->website : 'Not specified'; ?>
          <br/>
          <strong>Instagram photos: </strong>
          <?php echo $result->data->counts->media; ?>
          <br/>
          <strong>Followers: </strong>
          <?php echo $result->data->counts->followed_by; ?>
          <br/>
          <strong>Following: </strong>
          <?php echo $result->data->counts->follows; ?>
          <br/>
        </div>
    <?php
      } catch (Exception $e) {
        echo 'ERROR: ' . $e->getMessage() . print_r($client);
        exit;
      }
    }
  ?>
  </body>
</html>
