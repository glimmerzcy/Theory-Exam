import { get } from '../../utils/request'
import React from 'react'
export default async store => {
  let { data } = (await get('api/main/page/v1'))
  let { notice, tests, ranks } = data;
  store.infoList.notice = notice.map((item, i) => {
    return {
      info: item.title,
      time: item.published_date,
      detail: item.content,
      url: `/notice?id=${i}`,
      ...item
    }
  })

  store.infoList.exam = tests.map((item, i) => {
    return {
      info: <span onClick={() => window.showToast('请在下方TABLE中操作')}>{item.name}</span>,
      time: item.publish_date,
      ...item
    }
  })

  ranks.rankByTime = ranks.rankByTime.map(v => [v.stu_id, v.real_name, v.score])
  ranks.rankByScore = ranks.rankByScore.map(v => [v.stu_id, v.real_name, v.score])
  store.infoList.stu_score_list = ranks
}
