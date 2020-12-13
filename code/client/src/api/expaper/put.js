import { post } from '@utils/request'
import React from 'react'
import syncAll from './all'
import sync from './get'

export default async store => {
  let paper
  try {
    paper = store.infoList.paper.getAUpLoad()
  } catch (error) {
    window.showToast(error.toString())
    return
  }
  await window.showLoading()
  let data = await post('api/college/common/paper/edit/v1', { paper })
  await window.hideLoading()
  let paper_id = data.data
  if (paper_id) {
    await syncAll(store)
    await sync(store, paper_id)
    await window.showToast('修改保存成功')
    return
  }
  window.showModal({ view: <h2>修改保存失败</h2> })
}
