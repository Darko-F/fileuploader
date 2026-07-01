<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.core');
?>
<form action="<?php echo Route::_('index.php?option=com_fileuploader'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form-validate">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="target_dir" class="form-label"><?php echo Text::_('COM_FILEUPLOADER_TARGET_DIR'); ?></label>
            <input type="text" name="target_dir" id="target_dir" class="form-control" value="<?php echo htmlspecialchars($this->targetDir, ENT_QUOTES, 'UTF-8'); ?>" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="folder" class="form-label"><?php echo Text::_('COM_FILEUPLOADER_SUBFOLDER'); ?></label>
            <input type="text" name="folder" id="folder" class="form-control" placeholder="optional subfolder" />
          </div>
        </div>
      </div>

      <div class="mb-3">
        <label for="files" class="form-label"><?php echo Text::_('COM_FILEUPLOADER_FILES'); ?></label>
        <input type="file" name="files[]" id="files" class="form-control" multiple webkitdirectory directory />
        <div class="form-text"><?php echo Text::_('COM_FILEUPLOADER_FILES_HELP'); ?></div>
      </div>

      <div class="form-check mb-3">
        <input type="checkbox" name="overwrite" id="overwrite" value="1" class="form-check-input" />
        <label for="overwrite" class="form-check-label"><?php echo Text::_('COM_FILEUPLOADER_OVERWRITE'); ?></label>
      </div>

      <button type="submit" class="btn btn-primary"><?php echo Text::_('COM_FILEUPLOADER_UPLOAD'); ?></button>
    </div>
  </div>
  <input type="hidden" name="task" value="upload" />
  <?php echo HTMLHelper::_('form.token'); ?>
</form>
