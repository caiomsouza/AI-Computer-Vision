<html>
  <head>
    <style>
    #follows {
      float: left;
      width: 400px;
      padding-right: 20px;
    }
    #followers {
      float: left;
      width: 400px;
      padding-right: 20px;
    }
    </style>
  </head>
  <body>
    <h1>Instagram User Relationships</h1>
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
      <h2>Relationships for '<?php echo $_POST['u']; ?>'</h2>
    <?php
      // userid 262796303
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

        // get who the user follows
        $client->setUri('https://api.instagram.com/v1/users/' .
          $id . '/follows');
        $client->setParameterGet('client_id', $CLIENT_ID);
        $response = $client->request();
        $result = json_decode($response->getBody());
        $follows = $result->data;
        unset($result);

        // get the user's followers
        $client->setUri('https://api.instagram.com/v1/users/' .
          $id . '/followed-by');
        $client->setParameterGet('client_id', $CLIENT_ID);
        $response = $client->request();
        $result = json_decode($response->getBody());
        $followers = $result->data;

    ?>
        <div id="follows">
          <h2>Follows</h2>
          <?php
          if (count($follows) > 0) {
            echo '<ul>';
            foreach ($follows as $item) {
              echo '<li style="display: inline-block; padding: 25px">
                <img src="' . $item->profile_picture . '" /> <br/>' .
                $item->username . ' (' . $item->full_name . ') </li>';
            }
            echo '</ul>';
          } else {
            echo 'Not following anyone';
          }
          ?>
        </div>

        <div id="followers">
          <h2>Followers</h2>
          <?php
          if (count($followers) > 0) {
            echo '<ul>';
            foreach ($followers as $item) {
              echo '<li style="display: inline-block; padding: 25px">
                <img src="' . $item->profile_picture . '" /> <br/>' .
                $item->username . ' (' . $item->full_name . ') </li>';
            }
            echo '</ul>';
          } else {
            echo 'No followers';
          }
          ?>
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
