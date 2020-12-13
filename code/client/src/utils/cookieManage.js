const cookieProxy = function() {
    const cookie = document.cookie
                    .split(';')
                    .reduce((acc, val) => (
                            {
                                [val.substr(0, val.indexOf('=')).trim()]: val.substr(val.indexOf('=') + 1),
                                ...acc
                            }
                        )
                    , {})
    const setCookie = (name, val) => document.cookie = `${name}=${val}`;
    const deleteCookie = (name) => document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:01 GMT;`;
    return new Proxy(cookie, {
        set: (obj, prop, val) => {
            setCookie(prop, val)
            Reflect.set(obj, prop, val)
        },
        deleteProperty: (obj, prop) => {
            deleteCookie(prop)
            Reflect.deleteProperty(obj, prop)
        }
    })

}


class cookieManage {
    constructor() {
        this.size = 52;
        this.cookieProxyInstance = cookieProxy();
    }

    storeCookie = (obj, name='submitData') => {
        let stringData = JSON.stringify(obj);

        let k = Math.floor(stringData.length / this.size);
        for (let i = 0; i <= k; i += 1) {
            this.cookieProxyInstance[name + i] = stringData.substr(i * this.size, this.size);
        }
    }

    getStoreCookie = (name='submitData') => {
        let stringData = '';
        for (let i = 0; this.cookieProxyInstance[name + i] !== void 0; ++i) {
            stringData += this.cookieProxyInstance[name + i];
        }
        return JSON.parse(stringData);
    }

    deleteStoreCookie = (name='submitData') => {
        for (let i = 0; this.cookieProxyInstance[name + i] !== void 0; ++i) {
            delete this.cookieProxyInstance[name + i];
        }
        return true;
    }
}

export default new cookieManage()




