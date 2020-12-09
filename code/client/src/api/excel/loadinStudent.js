import React from 'react'
import XLSX from 'xlsx'

export default async (store, getFile) => {
  let file = getFile()
  if (!file) return
  let res
  await window.showLoading(() =>
    res.msg === true
      ? window.showToast('导入成功')
      : window.showModal({ view: <h2>导入失败:{res.msg}!</h2> })
  )
  const parseFromFile = async e => {
    try {
        let sheets = XLSX.read(new Uint8Array(e.target.result), { type: 'array' }).Sheets
        let students = XLSX.utils.sheet_to_json(sheets['Sheet1'])
        if (!students[0]) throw '格式不正确'
        let stu_ids = store.infoList.stu_table.reduce((acc, val) => acc[val[0]] = val[1] , {})
        for (let student of students) {
            let id = +student['学号']
            if (!stu_ids[id]) {
                store.infoList.stu_table.push([id, student['姓名']])
                stu_ids[id] = student['姓名']
            }
        }
        store.infoList.stu_ids = stu_ids
        window.hideLoading()
        return { msg: true }
    } catch (msg) {
        window.hideLoading()
        return { msg }
    }
  }

  let reader = new FileReader()
  reader.onload = async e => res = await parseFromFile(e)
  reader.readAsArrayBuffer(file)
}
