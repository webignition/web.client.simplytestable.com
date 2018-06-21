$(document).ready(function() {

    var buildSortRowFromFirstTask = function (selector, scope, errorColumnNoun) {
        var firstRow = $(selector, scope).first().clone().addClass('sort');

        $('.url-container', firstRow).html('<p class="sort-control link" data-sort-by="url" data-sort-by-type="string">Sort by URL <i class="fa fa-caret-down"></i></p>');
        $('.occurrences-container', firstRow).html('<p class="sort-control sorted" data-sort-by="occurrences" data-sort-by-type="number" data-sort-by-comparator-pattern="[0-9]+" data-sort-by-secondary="url">Sorted by ' + errorColumnNoun + ' <i class="fa fa-caret-down"></i></p>');

        return firstRow;
    };

    var sortControlOnClick = function (selectedControl, sorter) {
        if (selectedControl.is('.sorted')) {
            return;
        }

        if (typeof sorter == 'function') {
            sorter(selectedControl);
        }

        selectedControl.removeClass('link').text(selectedControl.text().replace('Sort', 'Sorted'));
        $('.fa', selectedControl).remove();

        $('.sort-control').each(function () {
            var control = $(this);
            control.removeClass('sorted unsorted');

            if (control.attr('data-sort-by') !== selectedControl.attr('data-sort-by')) {
                control.addClass('link').text(control.text().replace('Sorted', 'Sort'));
                control.append(' <i class="fa fa-caret-down"></i>');
            } else {
                control.addClass('sorted');
            }
        });
    };

    var sortBy = function (control, pagesContainer) {
        if (control.is('.sorted')) {
            return;
        }

        var items = $('.page', pagesContainer);
        var sortedItems = [];
        var index = [];
        var sortType = control.attr('data-sort-by-type');

        items.each(function (position) {
            var item = $(this);
            var comparatorContent = jQuery.trim($('.' + control.attr('data-sort-by') + '-container', item).text());
            var comparatorValue = '';

            if (control.attr('data-sort-by-comparator-pattern')) {
                comparatorValue = comparatorContent.match(new RegExp(control.attr('data-sort-by-comparator-pattern')))[0];
            } else {
                comparatorValue = comparatorContent;
            }

            if (sortType === 'number') {
                comparatorValue = parseInt(comparatorValue, 10);
            }

            index.push({'position': position, 'value': comparatorValue});
        });

        index.sort(function(a,b) {
            if (sortType === 'string') {
                if ( a.value < b.value ) {
                    return -1;
                }

                if ( a.value > b.value ) {
                    return 1;
                }

                return 0;
            } else {
                return a.value - b.value;
            }
        });

        if (sortType === 'number') {
            index.reverse();
        }

        if (control.attr('data-sort-by-secondary')) {
            var containsMultiple = function (value) {
                var count = 0;

                for (var indexPosition = 0; indexPosition < index.length; indexPosition++) {
                    if (index[indexPosition].value === value) {
                        count++;
                    }
                }

                return count > 1;
            };

            var getPositionsForValue = function (value) {
                var positions = [];

                for (var indexPosition = 0; indexPosition < index.length; indexPosition++) {
                    if (index[indexPosition].value === value) {
                        positions.push(index[indexPosition].position);
                    }
                }

                return positions;
            };

            var newIndex = [];
            var processedValues = [];

            for (var indexIndex = 0; indexIndex < index.length; indexIndex++) {
                if (processedValues.indexOf(index[indexIndex].value) !== -1) {
                    continue;
                }

                if (containsMultiple(index[indexIndex].value)) {
                    var positions = getPositionsForValue(index[indexIndex].value);
                    var secondaryIndex = [];

                    for (var positionIndex = 0; positionIndex < positions.length; positionIndex++) {
                        var item = items.get(positions[positionIndex]);
                        secondaryIndex.push({'position': positions[positionIndex], value: jQuery.trim($('.' + control.attr('data-sort-by-secondary') + '-container', item).text())});
                    }

                    secondaryIndex.sort(function(a,b) {
                        return a.value - b.value;
                    }).reverse();

                    for (var secondaryIndexIndex = 0; secondaryIndexIndex < secondaryIndex.length; secondaryIndexIndex++) {
                        newIndex.push({'position': secondaryIndex[secondaryIndexIndex].position, 'value': index[indexIndex].value});
                    }
                } else {
                    newIndex.push(index[indexIndex]);
                }

                processedValues.push(index[indexIndex].value);
            }


            index = newIndex;
        }

        for (var indexPosition = 0; indexPosition < index.length; indexPosition++) {
            sortedItems.push($(items.get(index[indexPosition].position)).clone());
        }

        $('.page', pagesContainer).remove();

        for (var sortedItemIndex = 0; sortedItemIndex < sortedItems.length; sortedItemIndex++) {
            pagesContainer.append(sortedItems[sortedItemIndex]);
        }
    };

    var initialiseByPageList = function () {
        var firstRow = buildSortRowFromFirstTask('.by-page-list .task', undefined, 'error count');
        $('.by-page-list').before(firstRow);

        $('.sort-control', firstRow).click(function () {
            var selectedControl = $(this);

            sortControlOnClick(selectedControl, function () {
                sortBy(selectedControl, $('.by-page-list'));
            });
        });
    };

    var initialiseByErrorList = function () {
        $('.by-error-list li.error').each(function () {
            var error = $(this);
            var list = $('.pages', error);

            if ($('.page', list).length === 1) {
                return;
            }

            var firstRow = buildSortRowFromFirstTask('.task', list, 'occurrences');

            list.prepend(firstRow);

            $('.sort-control', firstRow).click(function () {
                var selectedControl = $(this);

                sortControlOnClick(selectedControl, function () {
                    sortBy(selectedControl, selectedControl.closest('.pages'));
                });
            });

            if ($('.page', list).length > 6) {
                var controller = $('p.lead', error);
                list.addClass('collapse');

                $('.colon', controller).remove();

                controller.click(function (event) {
                    list.collapse('toggle');
                    event.preventDefault();
                }).css({
                    'cursor':'pointer'
                }).append(
                    $('<a class="caret-container" href="#"><i class="fa fa-caret-down"></i> show page list</a>')
                );

                list.on('shown.bs.collapse', function () {
                    $('a', controller).remove();
                    controller.append(
                        $('<a class="caret-container" href="#"><i class="fa fa-caret-up"></i> hide page list</a>')
                    );
                }).on('hidden.bs.collapse', function () {
                    $('a', controller).remove();
                    controller.append(
                        $('<a class="caret-container" href="#"><i class="fa fa-caret-down"></i> show page list</a>')
                    );
                });
            }
        });
    };

    // initialiseByErrorList();
    // initialiseByPageList();

});
