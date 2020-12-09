import React from 'react'
import Store from '../../utils/Store'
import RouteConfig from '../RouteConfig'

let searchValue = ''

export const mypaper = {
  more: () => RouteConfig.MoreExam.route,
  table: [
    {
      title: { info: '可参加的考试' },
      head: ['考试', '状态', '剩余次数', '当前成绩']
    },
    {
        title: { info: '即将开始的考试' },
        head: ['考试', '状态', '剩余次数', '当前成绩']
    },
    {
      title: { info: '已完结的考试' },
      head: ['考试', '状态', '参考次数', '成绩']
    },
    {
      title: {
        info: <>
          <input
            placeholder='搜索想找到的考试'
            onChange={e => {
              searchValue = e.target.value
              Store.request('exam/search', e.target.value)()
            }
            } />
          <span className='responsive iw-confirm' style={{ padding: '3px' }} onClick={() => Store.request('exam/search', searchValue)()}>搜索</span>
        </>
      },
      head: ['考试', '状态', '次数', '成绩']
    }
  ]
}
