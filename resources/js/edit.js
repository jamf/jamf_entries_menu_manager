$(document).ready(function() {
	$('.sortable').nestedSortable({
		handle: '.handle',
		items: 'li',
		toleranceElement: '> div',
		maxLevels: 2,
		placeholder: "ui-state-highlight",
		forcePlaceholderSize: true,
		connectWith: ".connectedSortable",
		stop: function(event, ui) {
			updateInputs();
		},
		isAllowed: function (placeholder, placeholderParent, currentItem) {
			// allow all items to be top-level
			if (placeholderParent == null) {
                return true;
            }

    		// don't allow titles to be children
            if ($(currentItem).hasClass('title')) {
            	return false;
	        }

            return true;
		}
	});

	// TODO: add "clone" option to list same channel in multiple places?

	$('body').on('click', '.add-title', function(event) {
		var count = $('.title').length;

		var $text = $('#text-template_0').clone();
		$text.removeClass('hidden template');
		$text.attr('id', 'title_' + count);
		$text.find('div').attr('data-id', count);
		$text.find('input').attr('name', 'title_' + count);
		$text.find('input').attr('required', 'required');

		var parentLi = event.target.closest('li');

		$(parentLi).before($text);

		// create placeholder for new children
		$text.append('<ol></ol>');

		// first, move any children of the channel as children of the title
		var $children = $(parentLi).find('li');
		$text.find('ol').append($children);

		// then move the current item as the first child of the title
		$text.find('ol').prepend(parentLi);

		updateInputs();
	});

	$('body').on('click', '.remove-title', function(event) {
		var $parentLi = $(event.target).closest('li');

		var $childOl = $parentLi.find('ol');

		// need to move any child elements to root
		if ($childOl.length > 0) {
			$childOl.find('li').each(function(index, item) {
				$parentLi.before(item);
			});
		}

		$parentLi.remove();

		updateInputs();
	});

	// normal submit form
	$('#emm-form').on('submit', function(event) {
		// trigger any errors on input fields
		$('li.title').not('.template').each(function(index, item) {
			$(item).find('input').get(0).reportValidity();
		});
	});

	// reset form
	$('#emm-form-reset').on('submit', function(event) {
		if (!confirm('Are you sure you want to reset the layout to the default channel order? This cannot be undone except by manually rebuilding the current layout.')) {
			event.preventDefault();
		}
	});

	var updateInputs = function() {
		$('.sortable').each(function(index, item) {
			var index = $(item).attr('data-list-id');

			$('#tree' + index).val($(item).nestedSortable('serialize'));
		});

		// ensure all titles have children (we'll call reportValidity on submit to show the error)
		$('li.title').not('.template').each(function(index, item) {
			var input = $(item).find('input').get(0);

			if ($(item).find('ol li.channel').length === 0) {
				input.setCustomValidity('Titles must have at least one child channel.');
			} else {
				input.setCustomValidity('');
			}
		});
	};

	// trigger on page load in case the tree data from PHP removed some data that the serialized data was expected to have
	// i.e. if a channel was deleted from the CMS, but EMM hasn't been resaved yet
	updateInputs();
});