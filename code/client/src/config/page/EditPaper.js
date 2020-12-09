import React from 'react'
import Route from '../RouteConfig'
import ConfigModal from '../../components/PaperConfig/index'
import Store from '../../utils/Store'

export const func_icon = [
  {
    icon: 'home',
    click: () => window.navigateTo(Route.ExPaper.route),
    alt: '返回试卷页',
    title:'返回首页'
  },
  {
    icon: 'config',
    click: () => window.showView(<ConfigModal />),
    alt: '设置',
    title:'设置试卷头'
  },
  {
    icon: 'preview',
    click() {
      if (!Store.infoList.paper.canToExam()) {
        window.showToast('题目数不足出题数下限')
        return
      }
      Store.infoList.exam_paper = Store.infoList.paper.getAExam()
      window.navigateTo(Route.Exam.route)
    },
    alt: '预览',
    title:'预览试卷'
  },
  {
    icon: 'save',
    alt: '保存',
    title:'保存试卷',
    click: Store.request('expaper/put')
  },
  {
    icon: 'send',
    alt: '发布考试',
    title:'发布考试',
    click() {
      Store.infoList.stu_table = []
      for (let body of Store.infoList.paper.body) {
          let quiz = body.quiz
          if (quiz.length < body.config.num) {
            window.showToast('题目数量不足最低限制，无法发布')
            return
          }
      }

      if (Store.infoList.paper.calculateScore() < 100) {
        window.showToast('分数不足100分')
        return
      }
      if (!Store.infoList.paper.head.id) {
        window.showToast(' 请先保存题目后，再行发布')
        return
      }
      if (Store.infoList.paper.head.aim == 0) {
            Store.request('expaper/publish')()
            return
      }

      window.navigateTo(Route.SelStu.route)
    }
  },
]
