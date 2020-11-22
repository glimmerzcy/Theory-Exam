import React from 'react'
import { Link } from 'react-router-dom'
import { get } from '../../utils/request'

export default async store => {
  let res = (await get('api/getTests')).data
  store.infoList.mypaper = new Array(3).fill([])
  res.forEach(p => {
    if (p.status != '已结束' && p.tested_time - p.test_time < 0)
      store.infoList.mypaper[0].push([p.name, <Link onClick={store.request('exam/get')}>参加考试</Link>, p.test_time - p.tested_time, p.score])
    else
      store.infoList.mypaper[1].push([p.name, p.stu_status, p.tested_time, p.score])
  })
}
