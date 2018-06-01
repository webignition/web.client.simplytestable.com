let unavailableTaskTypeModalLauncher = require('../unavailable-task-type-modal-launcher');

module.exports = function (document) {
    unavailableTaskTypeModalLauncher(document.querySelectorAll('.task-type-option.not-available'));
};
