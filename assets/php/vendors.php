<div class="body-widget">
    <h2>Our Sponsors</h2>
    <?php foreach (array_merge($vendorsL, $vendorsR) as $vendor) {
        echo "
              <div class='body-widget'>
              <a href='{$vendor["url"]}'>
                  <img src='{$vendor["img"]}'>
              </a>
          </div>
              ";
    } ?>
</div>