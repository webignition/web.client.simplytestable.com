class TaskIdList {
    /**
     * @param {number[]} taskIds
     * @param {number} pageLength
     */
    constructor (taskIds, pageLength) {
        this.taskIds = taskIds;
        this.pageLength = pageLength;
    }

    /**
     * @param {number} pageIndex
     *
     * @returns {number[]}
     */
    getForPage (pageIndex) {
        let pageNumber = pageIndex + 1;

        return this.taskIds.slice(pageIndex * this.pageLength, pageNumber * this.pageLength);
    };
}

module.exports = TaskIdList;
