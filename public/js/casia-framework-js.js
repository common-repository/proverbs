//version: 0.2
//functions

//height equalizing of elements
$=jQuery
casia = {}

casia.applyHeight = function(elements, height) {
	$.each(elements, function(key, value) {
		var outerHeight = $(value).outerHeight()
		if (outerHeight!=height) {
			$(value).outerHeight(height)
		}
	})
}

casia.allToHighestHight = function(elements) {
	var maxHeight = casia.findHighestHight(elements)
	casia.applyHeight(elements, maxHeight)
}

casia.findHighestHight = function(elements) {
	var heights = []

	$.each(elements, function( key, value ) {
		heights.push($(value).outerHeight())
	})

	var maxHeight = Math.max.apply(null, heights);
	return maxHeight
}

casia.toHighestClassInClass = function(container, element) {
	$(container).each(function() {
		var boxes = []

		$(this).find(element).each(function(i, obj) {
			boxes.push(obj)
		})

		casia.allToHighestHight(boxes)
	})
}

//working with query parameters
casia.getQueryParams = function() {
	var params = location.search.substr(1).split('&'),
	results = {};

	for(var i = 0; i < params.length; i++)
	{
		var temp = params[i].split('='),
			key = temp[0],
			val = temp[1];

		results[key] = results[key] || [];
		results[key].push(val);
	}

	return results;
}

//arrays
casia.isInArray = function(value, array) {
	if(array != undefined) {
		return array.indexOf(value) > -1;
	}
	return false;
}

//forms
casia.checkboxesChecked = function(selector, values) {
	$.each(values, function(i) {
		values[i] = this.replace("+"," ");
	});

	$(selector).each(function(){
		if (casia.isInArray($(this).attr('value'), values)) {
			$(this).prop('checked', true);
		}
	});
}

//tables
casia.paginatedTable = function(amountRows) {
	$('.casia-paginated-table').after('<div id="nav"></div>');
	var rowsShown = amountRows;
	var rowsTotal = $('.casia-paginated-table tbody tr').length;
	var numPages = rowsTotal/rowsShown;
	for(i = 0;i < numPages;i++) {
		var pageNum = i + 1;
		$('#nav').append('<a href="#" rel="'+i+'">'+pageNum+'</a> ');
	}
	$('.casia-paginated-table tbody tr').hide();
	$('.casia-paginated-table tbody tr').slice(0, rowsShown).show();
	$('#nav a:first').addClass('active');
	$('#nav a').bind('click', function(){

		$('#nav a').removeClass('active');
		$(this).addClass('active');
		var currPage = $(this).attr('rel');
		var startItem = currPage * rowsShown;
		var endItem = startItem + rowsShown;
		$('.casia-paginated-table tbody tr').css('opacity','0.0').hide().slice(startItem, endItem).
		css('display','table-row').animate({opacity:1}, 300);
	});
}

c_ = casia;