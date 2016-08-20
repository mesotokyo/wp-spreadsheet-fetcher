<div class="wrap">
  <h2>Add/Edit Spreadsheet</h2>

  <form name="spsf-main-form" method="post" action="">
    <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
    <input type="hidden" name="id" value="<?php echo $sps_values['id']; ?>">

    <p>
      <label for="slug">slug:</label>
      <input type="text" name="slug" id="slug" size="72" value="<?php echo $sps_values['slug']; ?>">
    </p>

    <p>
      <label for="title">title:</label>
      <input type="text" name="title" id="title" size="72" value="<?php echo $sps_values['title']; ?>">
    </p>

    <p>
      <label for="description">description:</label>
      <textarea name="description" id="description" rows="4" cols="72"><?php echo $sps_values['description']; ?></textarea>
    </p>

    <p>
      <label for="source_type">source_type:</label>
      <input type="text" name="source_type" id="source_type" size="72" value="<?php echo $sps_values['source_type']; ?>">
    </p>

    <p>
      <label for="source">source:</label>
      <input type="text" name="source" id="source" size="72" value="<?php echo $sps_values['source']; ?>">
    </p>

    <p>
      <label for="template">template:</label>
      <textarea name="template" id="template" rows="4" cols="72"><?php echo $sps_values['template']; ?></textarea>
    </p>

    <p>
      <label for="options">options:</label>
      <textarea name="options" id="options" rows="4" cols="72"><?php echo $sps_values['options']; ?></textarea>
    </p>

    <hr/>

    <p class="submit">
      <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
    </p>

  </form>
</div>
