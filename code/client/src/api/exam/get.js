import React from 'react'
import { post } from '../../utils/request'
import { Exam } from '../../config/ClassDefine'
import Route from '../../config/RouteConfig'
import cookieManage from '../../utils/cookieManage'
import { submitAnswer } from '../../pages/Exam/Exam'

export default async (store, { paper_id, title, tip, duration, ended_at }) => {
    if (new Date() > new Date(ended_at)) {
        window.showToast('考试已经结束')
        window.navigateTo(Route.Student.route)
        return
    }

  let bar = async () => {
    window.showModal({
      view: <h4>检测到本地储存有上次未提交成功的记录, 点击确认将为您重新提交，点击取消将作废上次答题</h4>,
      onConfirm: submitAnswer,
      onCancel: () => cookieManage.deleteStoreCookie()
    })
  }
  if (cookieManage.cookieProxyInstance.submitData0) {
    await bar()
  } else {
    window.showModal({
      view: (
        <div>
          <h2>Warm Tip 温馨提示</h2>
          {
              tip
              ? <p class='html-text'>{tip}</p>
              : null
          }
          <p>In order to avoid any accident, please do not let your room-mate run or romp about in front of screen or climb the seats or hold the crack of the door or touch the electric water boiler，when you are taking this online exam.</p>
          <p>当你在进行考试的时候，请勿让你的室友在屏幕前奔跑、打闹、攀爬座椅、手扶门缝、触碰电茶炉等，以免发生意外伤害。</p>
          <h4>
              推荐使用 Chrome/FireFox/Safari 浏览器，最好使用校园网进行答题。不要使用手机、微信和QQ内置浏览器进行答题。
          </h4>
        </div>
      ),
      onConfirm: async () => {
        await window.showLoading()
        let res = await post('api/student/test/draw/v1', { paper_id })
        let exam = new Exam(), { data } = res
        if (!data || !data.questions || !data.questions[0]) {
            window.showToast('没有答题资格')
            window.navigateTo(Route.Student.route)
        }
        exam.head = {
          ...store.infoList.exam_paper.head,
          paper_id: data.paper_id,
          title: title,
          num: data.total_number,
          duration,
          start_date: new Date().getTime(),
        }
        let question = [], subjective = []
        data.questions.forEach((ele, type) => {
            ele.question = ele.question
            .map(q => (
                {
                    ...q,
                    type,
                }
            ))

            if (+ele.is_subjective) {
                subjective.push(ele)
            } else {
                question.push(ele)
            }
        })
        exam.body = question
        exam.subjective = subjective;

        store.infoList.exam_paper = exam
        window.navigateTo(Route.Exam.route)
        await window.hideLoading()
      },
      confirmText:'开始考试'
    })
  }
}

