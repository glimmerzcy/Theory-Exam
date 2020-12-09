import Route from '../RouteConfig'
import Store from '../../utils/Store'
import React from 'react'

export const func_icon = (id, title) => [
  [
    {
      icon: 'edit',
      click: () => {
        window.navigateTo(Route.EditPaper.route)
        Store.request('expaper/get', id)()
      },
      title: '编辑试卷'
    },
    {
      icon: 'save',
      title: '试卷另存为',
      click: async () =>
        await window.showModal({
          view: <>
            <h2>另存为</h2>
            <p>将由本张试卷创建新的考试</p>
          </>,
          onConfirm: () => {
            window.navigateTo(Route.EditPaper.route)
            Store.request('expaper/copy', id)()
          }
        })
    },
  ],
  [
    {
      icon: 'func',
      title: '导出考试成绩信息',
      click: async () => {
        await window.showLoading()
        await (Store.request('excel/export', { id, title })())
        await window.hideLoading()
      }
    },
    {
      icon: 'datum',
      title: '数据概览',
      click: async () => {
        await window.showLoading()
        await (Store.request('expaper/datum', id)())
        await window.hideLoading()
        window.navigateTo(Route.ExDetail.route)
      }
    },
  ]
]
