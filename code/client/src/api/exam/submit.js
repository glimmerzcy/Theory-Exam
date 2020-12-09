import { post } from '../../utils/request'
import cookieManage from '../../utils/cookieManage'
import React from 'react'


export default async store => {

  let submitFunc = store.infoList.exam_paper.submit
  let result
  if (typeof submitFunc === 'function') {
    result = store.infoList.exam_paper.submit()
  } else {
    result = cookieManage.getStoreCookie()
  }
  try {
    var res = await post('api/student/test/score/v1', result)
  } catch(error) {
    cookieManage.deleteStoreCookie()
    cookieManage.storeCookie(result)
    window.iwType() === 'loading' && await window.hideLoading()
    return async () => await window.showModal({
      view:
      <>
        <h4>{error.toString()}</h4>
        <p>请确保网络连接稳定，稍后再重新提交</p>
        <p>已经将本次作答记录储存在本地，将会在下次您打开首页的时候自动为您提交</p>
      </>
    })
  }

  return async () => {
    cookieManage.deleteStoreCookie()
    window.iwType() === 'loading' && await window.hideLoading()
    return await window.showModal({
        view: <h3>得分:{res.data}</h3>,
        onConfirm: async () => {
            await store.request('log/status')()
            window.navigateTo('/student')
        },
        onCancel: async () => {
            await store.request('log/status')()
            window.navigateTo('/student')
        }
    })
  }

}
