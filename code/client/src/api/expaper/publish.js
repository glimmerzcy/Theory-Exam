import { post } from '@utils/request'
import Route from '@config/RouteConfig'

export default async (store) => {
    let postData
    if (store.infoList.paper.head.aim === 0) {
        postData = {
            paper_id: store.infoList.paper.head.id
        }
    } else if (store.infoList.paper.head.aim === 1) {
        postData = {
            paper_id: store.infoList.paper.head.id,
            college_codes: store.infoList.college_codes.map(ele => ele.id),
        }
    } else {
        postData = {
            paper_id: store.infoList.paper.head.id,
            stu_ids: store.infoList.stu_table.map(stu => stu[0])
        }
    }
    let res = await post('api/college/paper/release/v1', postData)
    if (res.status === 'succeed') {
        await window.showToast('发布成功')
        window.navigateTo(Route.ExPaper.route)
    }
}
