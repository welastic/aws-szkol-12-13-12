<?php
      $url = "http://169.254.169.254/latest/meta-data/instance-id";
      $instance_id = file_get_contents($url);
      echo "<h1>Hello World</h1>";
      echo "Instance ID: <b>" . $instance_id . "</b><br/>";
      $url = "http://169.254.169.254/latest/meta-data/placement/availability-zone";
      $zone = file_get_contents($url);
      echo "Zone: <b>" . $zone . "</b><br/>";
      $url = "http://169.254.169.254/latest/meta-data/local-ipv4/";
      $privateIP = file_get_contents($url);
      echo "Private IP: <b>" . $privateIP .  "</b><br/>";

?>
