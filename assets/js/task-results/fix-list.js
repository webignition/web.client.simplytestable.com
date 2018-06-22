let IssueList = require('./issue-list');

class FixList extends IssueList {
    filterTo (fixHref) {
        [].forEach.call(this.issues, (issueElement) => {
            if (issueElement.querySelector('a').getAttribute('href') !== fixHref) {
                issueElement.parentElement.removeChild(issueElement);
            }
        });
    };
}

module.exports = FixList;
