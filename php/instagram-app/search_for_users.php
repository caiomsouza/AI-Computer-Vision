<html>
  <head></head>
  <body>
    <h1>Instagram User Search</h1>
    <?php
    if (!isset($_POST['submit'])) {
    ?>
    <form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
      Search for:
      <input type="text" name="q" />
      <input type="submit" name="submit" value="Search" />
    </form>
    <?php
    } else {
    ?>
    <h2>Search results for '<?php echo $_POST['q']; ?>'</h2>
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
        $client->setParameterGet('q', $_POST['q']);

        // get and display users
        $response = $client->request();
        $result = json_decode($response->getBody());
        $data = $result->data;
        if (count($data) > 0) {
          echo '<ul>';
          foreach ($data as $item) {
            echo '<li style="display: inline-block; padding: 25px">
              <img src="' . $item->profile_picture . '" /> <br/>' .
              $item->username . ' (' . $item->full_name . ') </li>';
          }
          echo '</ul>';
        }
      } catch (Exception $e) {
        echo 'ERROR: ' . $e->getMessage() . print_r($client);
        exit;
      }
    }
    ?>
  </body>
</html>
