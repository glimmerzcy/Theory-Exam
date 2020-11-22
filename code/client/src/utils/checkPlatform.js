export default function checkPlatform(func) {
    var ua = navigator.userAgent.toLowerCase();
    if (/MicroMessenger|QQ/i.test(ua)) {
        func && func('检测到您正在使用QQ或者微信内置浏览器，请尽量使用 PC端 Chrome/FireFox/Safari 进行答题。否则可能会存在提交失败等故障')
    }
    if(/android|iPhone|iPad|iPod|iOS|mobile/i.test(ua)){
        func && func('检测到您正在使用移动端答题，请尽量使用 PC端 Chrome/FireFox/Safari 进行答题。否则可能会存在提交失败等故障')
    }

    if (!/Chrome|FireFox|Safari/i.test(ua) || /edge/i.test(ua)) {
        func && func('检测到您不是正在使用PC端 Chrome/FireFox/Safari 之一，请尽量使用 PC端 Chrome/FireFox/Safari 进行答题。否则可能会存在提交失败等故障')
    }
}
