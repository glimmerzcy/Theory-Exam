export default function(target) {
    target.prototype.request = function (api, data) {
        return async () => (await import(`../api/${api}`)).default(this, data)
    }
}
