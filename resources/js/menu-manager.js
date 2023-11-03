var $section = $('.ee-sidebar__items-section');
var $entries = $section.find('.ee-sidebar__item[title="Entries"]');
var $entriesList = $('.navigation-submenu').first();

var defaultItems = {};

var $items = $entriesList.find('.dropdown__item').not(':first-of-type');

$items.each(function(index, item) {
	// parse channel ID from URL
	var href = item.firstElementChild.href.match(/filter_by_channel=(\d+)$/);

	// if the user doesn't have permission to edit this channel, its href="#" so the match fails
	// but we don't have any way to look up the channel ID, so we basically ignore it
	if (href !== null && href[1] !== undefined) {
		var channelId = href[1];
		defaultItems[channelId] = item;
	}

	// remove it from the DOM, and we'll reinsert it based on its tree position
	$(item).remove();
});

var output = '<div class="dropdown__wrapper column-width-'+colCount+'">';

for (let i=1; i<=colCount; i++) {
	output += '<div class="dropdown__column">';

	treeData[i].forEach((element, key) => {
		var childClass = element.children.length > 0 ? 'has-children' : '';
		var typeClass = 'type-' + element.type;

		output += '<div class="dropdown-item-wrapper ' + childClass + ' ' + typeClass + '">';

		if (element.type === 'channel') {
			output += defaultItems[element.id] ? defaultItems[element.id].outerHTML : '';
		} else if (element.type === 'title') {
			output += '<p>' + element.text + '</p>';
		}

		var childOutput = '';

		if (element.children.length > 0) {
			var childItems = [];

			element.children.forEach((childElement, childKey) => {
				if (childElement.type == 'channel') {
					if (defaultItems[childElement.id]) {
						childItems.push(defaultItems[childElement.id].outerHTML);
					}
				} else if (childElement.type === 'title') {
					childItems.push('<p>' + childElement.text + '</p>');
				}
			});

			childOutput = '<div class="entries-list child-wrapper child-count-' + childItems.length + '">' + childItems.join('') + '</div>';
		}

		output += childOutput + '</div>';
	});

	output += '</div>';
};

output += '</div>';

$entriesList.append(output);

// add &debug=1 to open the Entries menu on page load for easier debugging
var urlParams = new URLSearchParams(window.location.search);
var debug = urlParams.get('debug');

if (Number(debug)) {
	$section.addClass('has-open-dropdown');
	$entries.addClass('dropdown-open');
	$entriesList.addClass('dropdown--open');
}