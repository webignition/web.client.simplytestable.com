let Modal = require('bootstrap.native').Modal;

/**
 * @param {NodeList} taskTypeContainers
 */
module.exports = function (taskTypeContainers) {
    for (let i = 0; i < taskTypeContainers.length; i++) {
        let unavailableTaskType = taskTypeContainers[i];
        let taskTypeKey = unavailableTaskType.getAttribute('data-task-type');
        let modalId = taskTypeKey + '-account-required-modal';
        let modalElement = document.getElementById(modalId);
        let modal = new Modal(modalElement);

        unavailableTaskType.addEventListener('click', function () {
            modal.show();
        });
    }
};
