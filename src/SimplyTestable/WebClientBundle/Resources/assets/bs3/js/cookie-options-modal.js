$(document).ready(function() {
    var actionBadge = $('#cookies-action-badge');

    var modal = $('#cookies-options-modal');
    var tableBody = $('tbody', modal);

    var addButton = $('#cookies-options-add-cookie');
    var applyButton = $('[data-name=apply]', modal);

    var closedBy = $('input[name=closed-by]', modal);

    var previousTableBody = tableBody.clone();

    var formIsEmpty = function () {
        var isEmpty = true;

        $('input[type=text]', tableBody).each(function () {
            if ($(this).stFormHelper().isEmpty() === false) {
                isEmpty = false;
            }
        });

        return isEmpty;
    };

    var applyAndClose = function (event) {
        applyButton.click();
        event.preventDefault();
        event.stopPropagation();
    };

    var removeCookie = function (event) {
        var row = $(event.target).closest('tr');
        var rowCount = $('tr', tableBody).length;

        if (rowCount > 1) {
            row.remove();
            return;
        }

        $('input', row).each(function () {
            $(this).val('');
        });

        $($('input', row).first()).stFormHelper().select();
    };

    $('.remove', modal).each(function () {
        $(this).append(
            $('<i class="fa fa-trash-o" title="Remove this cookie"></i>').click(function (event) {
                removeCookie(event);
            })
        );
    });

    addButton.click(function () {
        var currentCookieCount = $('tr', tableBody).length;
        var newRow = $('tr', tableBody).last().clone();

        $('input', newRow).each(function () {
            var input = $(this);

            input.val('');

            if (input.attr('name').indexOf('name') !== -1) {
                input.attr('name', 'cookies['+currentCookieCount+'][name]');
            }

            if (input.attr('name').indexOf('value') !== -1) {
                input.attr('name', 'cookies['+currentCookieCount+'][value]');
            }

            input.keydown(function (event) {
                if (event.which === 13) {
                    applyAndClose(event);
                }
            });
        });

        $('.remove .fa', newRow).click(function (event) {
            removeCookie(event);
        });

        tableBody.append(newRow);

        window.setTimeout(function () {
            $('.name input', $('tr', tableBody).last()).stFormHelper().select();
        }, 100);

        event.preventDefault();
    });

    $('input[type=text]', modal).keydown(function (event) {
        if (event.which === 13) {
            applyAndClose(event);
        }
    });

    $('[data-name]', modal).click(function (event) {
        closedBy.val($(this).attr('data-name'));
    });

    modal.on('show.bs.modal', function () {
        previousTableBody = tableBody.clone();
    });

    modal.on('shown.bs.modal', function () {
        $('.name input', $('tr', tableBody).last()).stFormHelper().select();
    });

    modal.on('hide.bs.modal', function () {
        if (closedBy.val() == 'close') {
            tableBody.replaceWith(previousTableBody);
            tableBody = previousTableBody;
        }
    });

    modal.on('hidden.bs.modal', function () {
        if (formIsEmpty()) {
            $('.status', actionBadge).text('not enabled');
            actionBadge.removeClass('action-badge-enabled');
        } else {
            $('.status', actionBadge).text('enabled');
            actionBadge.addClass('action-badge-enabled');
        }
    });

});