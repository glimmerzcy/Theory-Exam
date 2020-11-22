import { get } from '../../utils/request'

export default async (store, paper_id) => {
  let res = await get('api/histogram', { paper_id })
  if (!res) return
  let chart = res.histogram.map((count, i) => ({ score: `${i * 25}~${(i + 1) * 25}`, count }))
  store.infoList.paper_detail = {...res,chart}
}
