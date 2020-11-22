import React from 'react'
import Store from '../../utils/Store'

import './ExDetail.css'

import Chart from '../../components/ScoreChart'

class ExDetail extends React.Component {
  render() {
    let { name, test, participated, rate, chart, time } = Store.infoList.paper_detail
    return (
      <div className='ex-detail-content'>
        <div className='ex-detail-head row aside'>试卷题目：{name || 'unknown'}</div>
        <div className='ex-detail-hr' />
        <div className='row aside'>
          <div className='ex-details column aside'>
            <div className='row aside'>
              <div className='ex-detail-small column'>
                <div>总题数</div>
                <span>{test || '0/0'}</span>
              </div>
              <div className='ex-detail-small column'>
                <div>考试参与人数</div>
                <span>{participated || '0/0'}</span>
              </div>
            </div>
            <div className='ex-detail-big column'>
              <div>考试通过比例：<span>{rate || '0/0'}</span></div>
              <div>考试时期：<span>{time || '1966.5--1976.10'}</span></div>
            </div>
          </div>
          <div className='ex-detail-chart'>
            <div className='ex-detail-chart-head'>分数数据</div>
            <Chart chart={chart} />
          </div>
        </div>
      </div>
    )
  }
}

export default ExDetail