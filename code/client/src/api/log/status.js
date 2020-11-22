import { get } from '../../utils/request'
import React from 'react'
import {
    Link
} from 'react-router-dom'
import Route from '../../config/RouteConfig'

export default async store => {
  let college_status, student_status
  await Promise.all([
    (async () => college_status = await get('api/college/login/status/v1'))(),
    (async () => student_status = await get('api/loginStatus'))()
  ])
  console.log('loginStatus', college_status, student_status)
  if(college_status.status === 'succeed'){
    store.state = {
      ...college_status,
      status: 'college',
      userName: college_status.name
    }
    window.navigateTo(Route.College.route)
  } else if(student_status.status === 'succeed'){
    let student_data = student_status.data.stu_info
    store.state = {
      ...student_data ,
      status: 'student',
      userName: student_data.real_name
    }
    store.infoList.mypaper = new Array(3).fill([])
    let papers = student_status.data.papers
    Object.keys(papers)
        .forEach(p => {
            let data = papers[p]
            if (data.status != '已结束' && data.tested_time - data.test_time < 0)
                store.infoList.mypaper[0].push([data.name, <Link onClick={store.request('exam/get', { paper_id: data.id, title: data.name, tip: data.tip })}>参加考试 </Link>, data.test_time - data.tested_time, data.score])
            else
                store.infoList.mypaper[1].push([data.name, data.stu_status, data.tested_time, data.score])
    })
    window.navigateTo(Route.Student.route)
  }
}
