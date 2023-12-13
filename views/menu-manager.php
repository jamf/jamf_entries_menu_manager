<!-- Copyright 2023, Jamf -->

<h1><?= lang('jamf_entries_menu_manager'); ?></h1>

<?=ee('CP/Alert')->get($alerts_name)?>

<p id="emm-instructions"><?= $instructions; ?></p>

<?php
	$addIcon = '<span class="add-title fa fa-plus-square" title="Add title"></span>';
	$removeIcon = '<span class="remove-title fa fa-times" title="Remove title"></span>';
	$handleIcon = '<span class="handle fa fa-bars fa-sm"></span>';
	// $cloneIcon = '<span class="clone-channel fa fa-copy"></span>';
?>

<form id="emm-form" action="<?= $save_url; ?>" method="POST">
	<input type="hidden" name="csrf_token" value="<?php echo CSRF_TOKEN ?>" />
	<div class="menu-manager">
		<?php foreach([1, 2, 3] as $listIndex) { ?>
			<input type="hidden" name="tree<?= $listIndex; ?>" id="tree<?= $listIndex; ?>" value="<?= $treeStrings[$listIndex]; ?>" />

			<ol class="sortable connectedSortable" data-list-id="<?= $listIndex; ?>">
				<?php foreach ($treeData[$listIndex] as $data) { ?>

					<li
						id="<?= $data['li_id']; ?>"
						class="<?= $data['type']; ?>"
					>
						<div
							data-id="<?= $data['li_id']; ?>"
						>
							<?= $handleIcon; ?>

							<?php if ($data['type'] === 'channel') { ?>
								<span class="channel-name">
									<?= $data['channel']; ?>
								</span>

								<?= $addIcon; ?>
							<?php } else if ($data['type'] === 'title') { ?>
								<input type="text" name="<?= $data['li_id']; ?>" value="<?= $data['text']; ?>" required />
								<?= $removeIcon; ?>
							<?php } ?>
						</div>

						<?php if (count($data['children']) > 0) { ?>
							<ol>
								<?php foreach($data['children'] as $childData) { ?>
									<li
										id="<?= $childData['li_id']; ?>"
										class="<?= $childData['type']; ?>"
									>
										<div
											data-id="<?= $childData['li_id']; ?>"
										>
											<?= $handleIcon; ?>

											<?php if ($childData['type'] === 'channel') { ?>
												<span class="channel-name">
													<?= $childData['channel']; ?>
												</span>

												<?= $addIcon; ?>
											<?php } ?>
										</div>
									</li>
								<?php } ?>
							</ol>
						<?php } ?>
					</li>
				<?php } ?>

				<?php if ($listIndex === 1) { ?>
					<!-- insert the input template -->
					<li id="text-template_0" class="title template">
						<div data-id="0">
							<?= $handleIcon; ?>
							<input type="text" />
							<?= $removeIcon; ?>
						</div>
					</li>
				<?php } ?>
			</ol>
		<?php } ?>
	</div>

</form>

<!-- this is a separate form so the data can be reset even if there's invalid data on the page -->
<form id="emm-form-reset" action="<?= $save_url; ?>" method="POST">
	<input type="hidden" name="csrf_token" value="<?php echo CSRF_TOKEN ?>" />
</form>

<button class="button button--primary" form="emm-form" name="submit" value="save">Save</button>
<button class="button button--secondary" form="emm-form-reset" name="submit" value="reset">Reset to default layout</button>