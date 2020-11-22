import { post } from '../../utils/request'

export default async (store, id) => {
  await window.showLoading()
  await post('api/college/paper/delete/v1',{ paper_id: id })
  let target = store.infoList.all_paper.filter(item => item.id == id)[0]
  if(target) store.infoList.all_paper.remove(target)
  await window.hideLoading()
}
