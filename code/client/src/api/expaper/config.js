import React from 'react'

import { get } from '@utils/request'
import { Paper } from '@config/ClassDefine'
import CM from '@components/PaperConfig/index'

export default async (store, paper_id) => {
  let res
  await window.showLoading(async () => res && window.showView(<CM />))
  res = await get('all_questions', { paper_id })
  let paper = await Paper.parseFromDB(res, paper_id, store.infoList.all_paper)
  store.infoList.paper = paper
}
