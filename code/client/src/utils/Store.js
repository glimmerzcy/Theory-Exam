import React from 'react'
import { Link } from 'react-router-dom'
import { observable } from 'mobx'
import storable from './ProtoStore'
import { Paper } from '../config/ClassDefine'

@storable
class Store {
  @observable state = {
    status: 'unknown',
    userName: 'unknown',
    inExam: false
  }
  @observable infoList = {
    //通知公告
    notice: [
      { info: '数学学院第36期形式与政策的通知', time: '2018-11-16', url: '/notice?id=0', detail: <span>考试时间：第12周周三（即11月21日）8：00至第13周周日（12月2日）24：01<br />鲁迅：其实地上本没有路，走的人多了，也便成了路</span> },
      { info: '数学学院第35期形式与政策的通知', time: '2018-11-15', url: '/notice?id=1', detail: '考试时间：第12周周三（即11月21日）8：00至第13周周日（12月2日）24：02' },
      { info: '数学学院第34期形式与政策的通知', time: '2018-11-14', url: '/notice?id=2', detail: '考试时间：第12周周三（即11月21日）8：00至第13周周日（12月2日）24：03' },
      { info: '数学学院第33期形式与政策的通知', time: '2018-11-13', url: '/notice?id=3', detail: '考试时间：第12周周三（即11月21日）8：00至第13周周日（12月2日）24：04' },
      { info: '数学学院第32期形式与政策的通知', time: '2018-11-12', url: '/notice?id=4', detail: '考试时间：第12周周三（即11月21日）8：00至第13周周日（12月2日）24：05' },
      { info: '数学学院第31期形式与政策的通知', time: '2018-11-11', url: '/notice?id=5', detail: '考试时间：第12周周三（即11月21日）8：00至第13周周日（12月2日）24：06' },
      { info: '数学学院第30期形式与政策的通知', time: '2018-11-10', url: '/notice?id=6', detail: '考试时间：第12周周三（即11月21日）8：00至第13周周日（12月2日）24：07' },
      { info: '数学学院第29期形式与政策的通知', time: '2018-11-9', url: '/notice?id=7', detail: '考试时间：第12周周三（即11月21日）8：00至第13周周日（12月2日）24：08' },
      { info: '数学学院第28期形式与政策的通知', time: '2018-11-8', url: '/notice?id=8', detail: '考试时间：第12周周三（即11月21日）8：00至第13周周日（12月2日）24：09' },
      { info: '数学学院第27期形式与政策的通知', time: '2018-11-7', url: '/notice?id=9', detail: '考试时间：第12周周三（即11月21日）8：00至第13周周日（12月2日）24：10' },
      { info: '数学学院第26期形式与政策的通知', time: '2018-11-6', url: '/notice?id=10', detail: '考试时间：第12周周三（即11月21日）8：00至第13周周日（12月2日）24：11' },
      { info: '数学学院第25期形式与政策的通知', time: '2018-11-5', url: '/notice?id=11', detail: '考试时间：第12周周三（即11月21日）8：00至第13周周日（12月2日）24：12' },
    ],
    //学习资料
    resource: [
      { info: '党课理论学习', time: '2018-11-16', url: '~https://www.twt.edu.cn/party/?page=study&do=text' },
      { info: '学习强国', time: '2018-11-16', url: '~https://www.xuexi.cn/' },
    ],
    //考试信息列表
    exam: [
      { info: '智能与计算学部第三十六期形式与政策课网上测试', time: '2018-11-16' },
      { info: '智能与计算学部第三十六期形式与政策课网上测试', time: '2018-11-16' },
      { info: '智能与计算学部第三十六期形式与政策课网上测试', time: '2018-11-16' },
      { info: '智能与计算学部第三十六期形式与政策课网上测试', time: '2018-11-16' },
      { info: '智能与计算学部第三十六期形式与政策课网上测试', time: '2018-11-16' },
    ],
    //我的考试
    mypaper: [
      [],
      [],
      []
    ],
    stu_score_list: {
        "rankByTime": [
            [
                "rankByTime",
                "***",
                99
            ]
        ],
        "rankByScore": [
            [
                "301****063",
                "***",
                99
            ]
        ]
    },
    //学院历次考试
    all_paper: [
      {
        title: '自动化学院第三十六期形式与政策课网上测试',
        date: '2018-11-16',
        status:'已完成',
        num: 1,
        rate: 45
      },
      {
        title: '自动化学院第三十六期形式与政策课网上测试',
        date: '2018-11-17',
        status:'已完成',
        num: 2134,
        rate: 1
      },
      {
        title: '自动化学院第三十六期形式与政策课网上测试',
        date: '2018-11-15',
        status:'已发布',
        num: 2134,
        rate: 45
      },
      {
        title: '自动化学院第三十六期形式与政策课网上测试',
        date: '2018-12-15',
        status:'未发布',
        num: 2134,
        rate: 45
      },
      {
        title: '自动化学院第三十六期形式与政策课网上测试',
        date: '2018-10-15',
        num: 2134,
        rate: 45
      },
      {
        title: '自动化学院第三十六期形式与政策课网上测试',
        date: '2017-10-15',
        num: 2134,
        rate: 45
      },
      {
        title: '自动化学院第三十六期形式与政策课网上测试',
        date: '2019-10-15',
        num: 2134,
        rate: 45
      },
    ],
    stu_table: [],
    stu_ids: {},
    stu_table_filter: new Array(9).fill([]),
    all_stus: [],
    tableFilter: [],
    all_college: [{id: 1, name: '加载中....'}],
    college_codes: [],
    //当前正在考试的试卷
    exam_paper: {
      head: {
        start_date: Date.now(),
        duration: 120,
        num: 70
      },
      body: [
        {
          question: []
        }
      ],
      subjective: [
        {
          question: []
        }
      ]
    },
    //当前正在编辑的试卷
    paper: new Paper(),
    paper_detail: {
      name: 'no name'
    }
  }
}

export default new Store()
