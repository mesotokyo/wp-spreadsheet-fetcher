<?php require_once(dirname(__FILE__) . '/html_helper.php'); ?>
<?php $HIDDEN_FIELD_NAME = 'spsf_hidden'; ?>
<?php $admin_url = admin_url("admin.php?page=sps-fetcher-edit-page");
 ?>
<div class="wrap">
  <h2>Add/Edit Spreadsheet</h2>

  <form name="spsf-main-form" method="post" action="<?php echo $admin_url ?>">
    <input type="hidden" name="<?php echo $HIDDEN_FIELD_NAME; ?>" value="Y">
    <input type="hidden" name="id" value="<?php echo $sps_values['id']; ?>">

    <p><?php form_item("text", "slug:", "slug", $sps_values['slug'], 'size="72"'); ?></p>

    <p><?php form_item("text", "title:", "title", $sps_values['title'], 'size="72"'); ?></p>

    <p><?php form_item("textarea", "description:", "description", $sps_values['description'], 'rows="4" cols="72"'); ?></p>

    <p><?php form_item("text", "source_type:", "source_type", $sps_values['source_type'], 'size="72"'); ?></p>

    <p><?php form_item("text", "source:", "source", $sps_values['source'], 'size="72"'); ?></p>

    <p><?php form_item("textarea", "template_header:", "template_header", $sps_values['template_header'], 'rows="4" cols="72"'); ?></p>

    <p><?php form_item("textarea", "template_body:", "template_body", $sps_values['template_body'], 'rows="4" cols="72"'); ?></p>

    <p><?php form_item("textarea", "template_footer:", "template_footer", $sps_values['template_footer'], 'rows="4" cols="72"'); ?></p>

    <p><?php form_item("textarea", "options:", "options", $sps_values['options'], 'rows="4" cols="72"'); ?></p>

    <hr/>
    <?php if ($preview): ?>
    preview:
    <div class="spsf_preview">
    <?php echo $preview; ?>
    </div>
    <hr/>
    <?php endif ?>

    <p class="submit">
      <input type="submit" name="submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
      <input type="submit" name="preview" class="button" value="preview" />
    </p>


  </form>
</div>
