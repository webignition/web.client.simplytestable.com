// Polyfill for browsers not supporting Object.entries()
// Lightly modified from polyfill provided at https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Object/entries#Polyfill
if (!Object.entries) {
    Object.entries = function (obj) {
        let ownProps = Object.keys(obj);
        let i = ownProps.length;
        let resArray = new Array(i);

        while (i--) {
            resArray[i] = [ownProps[i], obj[ownProps[i]]];
        }

        return resArray;
    };
}
