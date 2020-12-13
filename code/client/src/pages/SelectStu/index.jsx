import React from 'react'

import { fil_stu, college_head } from '@config/component/StuTable'
import StuTable from '@components/StuTable'
import CollegeTable from '@components/CollegeTable'
import { observer } from 'mobx-react'
import Store from '@utils/Store'

import './index.css'

let filter_arr = Store.infoList.tableFilter

@observer
class SelStu extends React.Component {
  constructor(props) {
    super(props)
    this.fileSelector = React.createRef()
  }
  reset = () => fil_stu.forEach((item, i) => filter_arr[i] = '')
  add = async () => {
      if (!filter_arr[0]) {
          return
      }
      if (Store.infoList.stu_ids[filter_arr[0]]) {
          window.showToast('该学生已经存在')
          return
      }
      Store.infoList.stu_table.push([...filter_arr])
      Store.infoList.stu_ids[filter_arr[0]] = filter_arr[1]

  }
  del = async () => {
    if (Store.infoList.stu_ids[filter_arr[0]]) {
        delete Store.infoList.stu_ids[filter_arr[0]]
    }
    Store.infoList.stu_table = Store.infoList.stu_table.filter(ele => ele[0] !== filter_arr[0])
  }
  onChange = i =>
    e => filter_arr[i] !== e.target.value ? filter_arr[i] = e.target.value : 0
  onChosen = (i, str) =>
    () => {
      if (filter_arr[i].indexOf(str) < 0)
        filter_arr[i] += (filter_arr[i][0] ? '|' : '') + str
      else{
        let set = new Set(filter_arr[i].split('|'))
        set.delete(str)
        filter_arr[i] = [...set].join('|')
      }
    }
  publish = () => {
    window.showModal({
      view: (
        <div>
          <h2>确认发布？</h2>
          <p>发布后将无法更改任何试卷设置</p>
          <p>发布后将无法编辑试题，修改参考学生</p>
          <p>请确认已经完成好所有操作，并不再修改！</p>
          <p>确认后将直接发布，<strong>请再次检测！</strong></p>
          <p><strong>确认无误后！</strong>进行发布</p>
        </div>
      ),
      confirmText: '确认发布',
      onConfirm: Store.request('expaper/publish')
    })
  }
  upload = async () => {
    window.showModal({
      view: <>
        <h2>确认导入</h2>
      </>,
      onConfirm: Store.request('excel/loadinStudent', () => this.fileSelector.current.files[0])
    })
  }
  render() {
    return (
      <div className='row aside'>
        <div className='fil-stu-table'>
            {
                Store.infoList.paper.head.aim === 2
                ? <StuTable head={fil_stu} />
                : <CollegeTable head={college_head} />
            }

          <p className='row around'>
            <div className='responsive iw-bn iw-confirm' onClick={this.publish}>保存并发布</div>
            <div className='responsive iw-bn iw-cancel' onClick={window.navigateBack}>返回</div>
          </p>
        </div>
        {
            Store.infoList.paper.head.aim === 2 ?
            <div className='fil-stu-er'>
                <div className='fil-stu-er-head'>添加条件</div>
                <div className='fil-stu-er-card column'>
                    {
                    fil_stu.map((item, i) =>
                        <div key={item} className='row aside'>
                        <span>{item}</span>
                        <div>
                            <input onChange={this.onChange(i)} value={filter_arr[i]} />
                        </div>
                        </div>
                    )
                    }
                </div>
                <div className='iw-bn iw-confirm' onClick={this.add}>添加</div>
                <div className='iw-bn iw-cancel' onClick={this.del}>移除</div>
                    <div className='fil-stu-er-head'>
                        <label className='responsive'>
                            导入学生列表文件
                            <input type='file' name='file' ref={this.fileSelector} multiple={false} accept='.xls,.xlsx'
                            onChange={this.upload} onClick={e => e.target.value = null} />
                        </label>
                        <span className='responsive il-stu-er-head' style={{'cursor': 'pointer'}} onClick={Store.request('excel/loadoutStudent')}>导出学生列表文件</span>
                    </div>
                </div>
            : <div className='fil-stu-er-head'>点击左边表格选择相应的学院， 红色表示已选</div>
        }
      </div>
    )
  }
}

export default SelStu
