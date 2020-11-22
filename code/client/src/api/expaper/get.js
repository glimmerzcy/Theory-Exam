import { post } from '../../utils/request'
import { Paper } from '../../config/ClassDefine'

export default async (store, paper_id) => {
  let res
  await window.showLoading()
  res = await post('api/college/common/paper/detail/v1', { paper_id })
  let paper = await Paper.parseFromDB(res, paper_id, store.infoList.all_paper)
  store.infoList.paper = paper
  await window.hideLoading()
}
