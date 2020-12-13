import React from 'react'
import { observer } from 'mobx-react'

import './StuTable.css'

import Store from '../.@utils/Store'

let filter = Store.infoList.tableFilter

@observer
class StuTable extends React.Component {
  static isShow(stu, isFilter = false) {
    if (!isFilter) {
      return true
    }
    let isshow = true
    stu.forEach((item, index) => {
      //not filter when empty
      if (!isshow || filter[index] === '')
        return
      let result = false
      filter[index].split('|').forEach(condition => {
        //filter in range
        if (condition.indexOf('-') > -1) {
          let range = condition.split('-')
          if (item >= range[0] && item <= range[1]) {
            result = true
            return
          }
          //filter in single condition
        } else {
          if (item === condition) {
            result = true
            return
          }
        }
      })
      if (!result) {
        isshow = false
      }
    })
    return isshow
  }
  render() {
    let { head, filter } = this.props
    return (
      <table className='stu-table'>
        <thead>
          <tr className='stu-table-head'>
            {head.map(text => <th className='stu-table-cell' key={'stu-tb-head-' + text}>{text}</th>)}
          </tr>
        </thead>
        <tbody className='stu-table-content'>
          {
            Store.infoList.stu_table.map((stu, i) =>
              StuTable.isShow(stu, filter)
              &&
              <tr className='stu-table-row' key={'stu-tb-' + i}>
                {stu.map((item, j) => <td className='stu-table-cell' key={'stu-tb-' + i + '-' + j}>{item}</td>)}
              </tr>
            )
          }
        </tbody>
      </table>
    )
  }
}

export default StuTable