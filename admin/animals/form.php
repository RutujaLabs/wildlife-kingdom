<?php if ($errors): ?>
  <div class="admin-alert admin-alert-error" role="alert">
    <?php foreach ($errors as $error): ?><p><?php echo htmlspecialchars($error); ?></p><?php endforeach; ?>
  </div>
<?php endif; ?>
<form class="admin-panel admin-form" method="post" enctype="multipart/form-data">
  <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(animal_csrf_token()); ?>">
  <?php if (isset($id)): ?><input type="hidden" name="id" value="<?php echo (int)$id; ?>"><?php endif; ?>
  <div class="admin-form-grid">
    <label>Animal name <span>*</span><input name="name" required maxlength="150" value="<?php echo htmlspecialchars($values['name'] ?? ''); ?>"></label>
    <label>Scientific name<input name="scientific_name" maxlength="180" value="<?php echo htmlspecialchars($values['scientific_name'] ?? ''); ?>"></label>
    <label>Habitat<select name="habitat_id"><option value="">Not assigned</option><?php foreach ($habitats as $habitatOption): ?><option value="<?php echo (int)$habitatOption['id']; ?>" <?php echo (string)($values['habitat_id'] ?? '') === (string)$habitatOption['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($habitatOption['name']); ?></option><?php endforeach; ?></select></label>
    <label>Conservation status<select name="conservation_status"><?php foreach (['Least Concern', 'Near Threatened', 'Vulnerable', 'Endangered', 'Critically Endangered'] as $status): ?><option value="<?php echo htmlspecialchars($status); ?>" <?php echo ($values['conservation_status'] ?? 'Least Concern') === $status ? 'selected' : ''; ?>><?php echo htmlspecialchars($status); ?></option><?php endforeach; ?></select></label>
    <label>Species<input name="species" maxlength="150" value="<?php echo htmlspecialchars($values['species'] ?? ''); ?>"></label>
    <label>Diet<input name="diet" maxlength="150" value="<?php echo htmlspecialchars($values['diet'] ?? ''); ?>"></label>
    <label>Lifespan<input name="lifespan" maxlength="100" value="<?php echo htmlspecialchars($values['lifespan'] ?? ''); ?>"></label>
    <label class="admin-checkbox"><input type="checkbox" name="is_featured" value="1" <?php echo !empty($values['is_featured']) ? 'checked' : ''; ?>> Feature on homepage</label>
    <label class="admin-full-width">Description<textarea name="description" rows="6" maxlength="10000"><?php echo htmlspecialchars($values['description'] ?? ''); ?></textarea></label>
    <label class="admin-full-width">Fun fact<textarea name="fun_fact" rows="3" maxlength="10000"><?php echo htmlspecialchars($values['fun_fact'] ?? ''); ?></textarea></label>
    <label class="admin-full-width">Animal image <span><?php echo isset($animal) && $animal['image'] !== '' ? '(optional; leave blank to keep current image)' : '(optional)'; ?></span>
      <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
      <small>JPG, PNG, GIF, or WEBP; maximum 5 MB.</small>
    </label>
  </div>
  <?php if (isset($animal) && $animal['image'] !== ''): ?>
    <div class="admin-current-image"><img src="../../uploads/animals/<?php echo rawurlencode($animal['image']); ?>" alt="Current animal image"><span>Current image</span></div>
  <?php endif; ?>
  <div class="admin-form-actions"><a href="list.php">Cancel</a><button class="admin-button" type="submit">Save Animal</button></div>
</form>