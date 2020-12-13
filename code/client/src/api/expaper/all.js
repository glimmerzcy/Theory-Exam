import { get } from '@utils/request'

const date_list = {
  '未发布':'updated_at',
  '已发布':'started_at',
  '已完成':'ended_time'
}

export default async store => {
  await window.showLoading()
  let res = await get('api/college/papers/v1')
  store.infoList.all_paper = res.data.map(paper => ({
    ...paper,
    title: paper.name,
    date:`${paper[date_list[paper.status]]}`.split(' ')[0]
  }))
  await window.hideLoading()

}
