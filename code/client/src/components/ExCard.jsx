import React from 'react'

import Store from '../utils/Store'
import { func_icon } from '../config/component/ExCard'
import './ExCard.css'

class ExCard extends React.Component {
  constructor(props) {
    super(props)
  }
  del = () => {
    window.showModal({
      view: <h2>确认删除？</h2>,
      onConfirm: Store.request('expaper/del', this.props.assign.id)
    })
  }
  render() {
    let { title, participated, rate, id, status } = this.props.assign
    const date_list = {
      '未发布': 'updated_at',
      '已发布': 'started_at',
      '已完成': 'ended_time'
    }
    let date = `${this.props.assign[date_list[status]]}`.split(' ')[0], funcs = func_icon(id)
    funcs = status === '未发布' ? [funcs[0]] : [[funcs[0][1],...funcs[1]]]
    return (
      <div className='excard'>
        <div className='excard-del'>
          <img className='excard-icon pointer' src={require('../assets/delete.png')} onClick={this.del} alt='delete'/>
        </div>
        <div className='excard-infos'>
          <div className='excard-title'>{title}</div>
          <div className='excard-date'>{date}</div>
          <div className='excard-date'>{status}</div>
        </div>
        <div className='excard-panel column aside'>
          {funcs.map(row =>
            <div className='row aside'>
              {row.map(item => <img title={item.title} className='excard-icon pointer' src={require(`../assets/${item.icon}.png`)} onClick={item.click} />)}
            </div>
          )}
        </div>
        <div className='excard-details column aside'>
          {
            status != '未发布'
            &&
            <div>
              <div className='excard-detail'>已有<span>{participated || 0}</span>人参加考试</div>
              <div className='excard-detail'>通过率<span>{rate || 'NaN'}</span>%</div>
            </div>
          }
        </div>
      </div>
    )
  }
}

export default ExCard
