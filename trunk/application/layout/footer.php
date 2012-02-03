<?php

$footerLinks = $this->registry->config->get('links:footer:+');

?>
    <span class="floatClear">&nbsp;</span>
    </div>
    <div class="footer"><ul>
    <?php foreach($footerLinks->link as $footerLink) {?>
      <li><a href="<?php print($footerLink->url); ?>"><?php print($footerLink->label); ?></a></li>
    <?php } ?></ul>
  </div>
</body>
</html>
