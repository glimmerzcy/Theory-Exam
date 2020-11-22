import React from 'react'

export default async (store, getFile) => {
  let file = getFile()
  if (!file) return
  let res
  await window.showLoading(() =>
    res.msg === true
      ? window.showToast('导入成功')
      : window.showModal({ view: <h2>导入失败:{res.msg}!</h2> })
  )
  let reader = new FileReader()
  reader.onload = async e => res = await store.infoList.paper.parseFromFile(e)
  reader.readAsArrayBuffer(file)
}