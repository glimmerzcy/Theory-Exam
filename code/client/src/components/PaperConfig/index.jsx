import React from 'react'
import { observer } from 'mobx-react'

import Store from '../../utils/Store'
import '../../utils/InnerWindow/InnerWindow'
import './index.css'

const onError = async msg => {
  await window.closeIW()
  window.showModal({
    view: <>
      <h2>保存失败</h2>
      <p style={{ color: 'red' }}>{msg || '不允许为空'}</p>
    </>,
    onConfirm: () => window.showView(<CM />)
  })
}

@observer
class CM extends React.Component {
  constructor(props) {
    super(props)
    this.state = { ...Store.infoList.paper.head }
  }
  config = {
    'time-limit': {
      check: v => v < 240 && v > 0,
      placeholder: '请输入0<x<240的数字'
    },
    'max-times': {
      check: v => v < 100 && v > 0,
      placeholder: '请输入0<x<100数字'
    },
    'min-score': {
      check: () => true,
      placeholder: '请输入'
    },
    'date-from': {
      check: () => true,
      placeholder: '请输入'
    },
    'date-to': {
      check: () => true,
      placeholder: '请输入'
    },
    'title': {
      check: () => true,
      placeholder: '试卷名称不能为空'
    },
    'tip': {
      check: () => true,
      placeholder: '无'
    },
    'aim': {
      check: () => true,
      placeholder: ''
    }
  }
  setClass = (key, isNum = false) =>
    ({
      key,
      onChange: this.onInput(key),
      value: this.state[key],
      placeholder: this.config[key].placeholder,
      onKeyPress: e => {
          let char = e.which || e.keyCode
          if (isNum && (char < 48 || char > 75))
            e.preventDefault()
      }
    })
  onInput = key =>
    e => {
      let { value } = e.target
      if (this.state[key] === value)
        return
      let { state } = this
      state[key] = this.config[key].check(value) ? value : ''
      this.setState(state)
    }
  close = () => {
    this.setState({ ...Store.infoList.paper.head })
    window.closeIW()
  }
  submit = async () => {
    //submit check will be done at here
    Store.infoList.paper.head = { ...this.state }
    const keys = ['title', 'time-limit', 'max-times', 'date-from', 'date-to']
    for (let key of keys)
      if (this.state[key] === '') {
        onError()
        return
      }
    if (new Date(this.state['date-from']) >= new Date(this.state['date-to'])) {
      onError('不允许结束日期早于开始日期')
      return
    }
    let { onConfirm } = this.props
    onConfirm && onConfirm()
    window.closeIW()
  }
  render() {
    let { setClass } = this
    return (
      <div>
        <div>
          <input className='ep-ip-title' minLength='1' maxLength='20' {...setClass('title')} />
          <div className='row aside ep-ip-inner'>
            <div className='column aside'>
              <div>时限(min)</div>
              <div>最大考试次数</div>
              <div>及格分数</div>
              <div>时间范围</div>
              <div>至</div>
              <div>考前提示</div>
              <div>考试范围</div>
            </div>
            <div className='column aside'>
              <input {...setClass('time-limit',true)} type='number' min={1} max={240} />
              <input {...setClass('max-times',true)} type='number' min={1} max={10 ** 3} />
              <input {...setClass('min-score',true)} disabled defaultValue='60' type='number' />
              <input {...setClass('date-from')} max={this.state['date-to']} type='datetime-local' />
              <input {...setClass('date-to')} min={this.state['date-from']} type='datetime-local' />
              <input {...setClass('tip')}  type='text' />
              <select {...setClass('aim')} >
                  <option value='0'>校级</option>
                  <option value='1'>院级</option>
                  <option value='2'>小型</option>
              </select>
            </div>
          </div>
        </div>
        <p className='row around'>
          <div className='responsive iw-bn iw-confirm' onClick={this.submit}>保存设置</div>
          <div className='responsive iw-bn iw-cancel' onClick={this.close}>取消</div>
        </p>
      </div>
    )
  }
}

export default CM
