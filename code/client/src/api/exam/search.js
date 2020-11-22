import { get } from '../../utils/request'
import React from 'react'
import { Link } from 'react-router-dom'

export default async (store, text) => {
  if(text === '')
    return
  let { data } = await get('api/student/search', { text })
  store.infoList.mypaper[2] = []
  data.forEach(p => {
    if (p.status != '已结束' && p.tested_time - p.test_time < 0)
      store.infoList.mypaper[2].push([p.name, <Link onClick={store.request('exam/get', p.id)}>参加考试</Link>, `${p.test_time - p.tested_time}/${p.tested_time}`, p.score])
    else
      store.infoList.mypaper[2].push([p.name, p.stu_status, p.tested_time, p.score])
  })
}