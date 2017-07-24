{$admin_messages}
<form action="" method="post">
  <fieldset>
    <legend>Settings</legend>
    <div>

      <div class="{cycle values='row_color1,row_color2'}">
        <div class="leftColumn">
          Base Image Host URL
          <img style="border-style: none;" src="admin_images/help.gif" alt="" class="tooltip" />
          <span class="tooltipTitleSpan" style="display: none;">base image url</span>
          <span class="tooltipTextSpan" style="display: none;">e.g. https://s3.amazonaws.com/photos.example.com</span>
        </div>
        <div class="rightColumn">
          <input type="text" size="70" name="settings[aws_access_key_id]" value="{$settings.base_url}" />
        </div>
        <div class="clearColumn"></div>
      </div>

      <div class="{cycle values='row_color1,row_color2'}">
        <div class="leftColumn">
          AWS S3 Bucket
          <img style="border-style: none;" src="admin_images/help.gif" alt="" class="tooltip" />
          <span class="tooltipTitleSpan" style="display: none;">s3 bucket</span>
          <span class="tooltipTextSpan" style="display: none;">e.g. photos.example.com</span>
        </div>
        <div class="rightColumn">
          <input type="text" size="70" name="settings[s3_bucket]" value="{$settings.s3_bucket}" />
        </div>
        <div class="clearColumn"></div>
      </div>

      <div class="{cycle values='row_color1,row_color2'}">
        <div class="leftColumn">
          AWS Access Key ID
          <img style="border-style: none;" src="admin_images/help.gif" alt="" class="tooltip" />
          <span class="tooltipTitleSpan" style="display: none;">aws access key id</span>
          <span class="tooltipTextSpan" style="display: none;">e.g. AKIAIOSFODNN7EXAMPLE</span>
        </div>
        <div class="rightColumn">
          <input type="text" size="70" name="settings[aws_key]" value="{$settings.aws_key}" />
        </div>
        <div class="clearColumn"></div>
      </div>

      <div class="{cycle values='row_color1,row_color2'}">
        <div class="leftColumn">
          AWS Secret Access Key
          <img style="border-style: none;" src="admin_images/help.gif" alt="" class="tooltip" />
          <span class="tooltipTitleSpan" style="display: none;">aws secret access key</span>
          <span class="tooltipTextSpan" style="display: none;">e.g. wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY</span>
        </div>
        <div class="rightColumn">
          <input type="text" size="70" name="settings[aws_secret]" value="{$settings.aws_secret}" />
        </div>
        <div class="clearColumn"></div>
      </div>

      <div class="center">
        <input type="submit" name="auto_save" class="mini_button" value="Save" />
      </div>
    </div>
  </fieldset>
</form>