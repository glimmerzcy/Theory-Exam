import { get } from '@utils/request'
import Route from '@config/RouteConfig'

export default async (store) => {
    let response
    if (store.state.status === 'college') {
        response = await get('api/college/logout/v1')
    } else {
        response = await get('api/logout')
    }
    if (response) {
        store.state.status = 'unknown'
        window.navigateTo(Route.Index.route)
        window.showToast('退出成功')
    } else {
        window.showToast('退出失败')
    }
}
