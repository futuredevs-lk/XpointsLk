<?php
function progress_bar($icon, $capacity, $value)
{
  echo  <<<EOT
  <div class="wallet-bal-wrapper" style="
          display: flex;
          background-color: rgb(180, 147, 40);
          border-radius: 10px 10px 27px 27px;
          background-image: url('https://cdn.wallpapersafari.com/43/80/IxCTBq.jpg');
          box-shadow: 0px 5px 12px 2px;
          padding: 2%;
          margin: auto;
        ">
  <div class="wal-icon" style="width: 4rem; height: 4rem">
    <img src="$icon" alt="" style="width: 100%" />
  </div>
  <div class="wal-bal" style="
            background-color: rgb(145 97 45);
            color: aliceblue;
            padding: 2%;
            border-radius: 10px;
            border: ridge 5px rgb(177, 130, 69);
            width: 60%;
            margin: auto;
            text-align: center;
          ">
    $capacity / $value
  </div>
</div>
EOT;
}
