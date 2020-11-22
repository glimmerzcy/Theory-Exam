import React from 'react'
import { observer } from 'mobx-react'

import './StuTable.css'

import Store from '../utils/Store'

const handleSelect = i => e => {
    Store.infoList.college_codes.push(Store.infoList.all_college[i])
    Store.infoList.all_college.splice(i, 1)
}
const handleUnselect = i => e => {
    Store.infoList.all_college.push(Store.infoList.college_codes[i])
    Store.infoList.college_codes.splice(i, 1)
}

@observer
class CollegeTable extends React.Component {
    constructor() {
        super()
        Store.request('selStu/getCollege')()
    }
  render() {
    let { head } = this.props
    console.log(Store.infoList.college_codes)
    return (
      <table className='stu-table'>
        <thead>
          <tr className='stu-table-head'>
            {head.map(text => <th className='stu-table-cell' key={'stu-tb-head-' + text}>{text}</th>)}
          </tr>
        </thead>
        <tbody className='stu-table-content'>
            {   Store.infoList.college_codes.length
                ? Store.infoList.college_codes.map((stu, i) =>
                        <tr className='stu-table-row selected' key={'selected' + i} onClick={handleUnselect(i)}>
                        <td className='stu-table-cell' key={stu['id']}>{stu['name']}</td>
                        </tr>
                    )
                : null
            }
          {
            Store.infoList.all_college.map((stu, i) =>
              <tr className='stu-table-row' key={'stu-tb-' + i} onClick={handleSelect(i)}>
                <td className='stu-table-cell' key={stu['id']}>{stu['name']}</td>
              </tr>
            )
          }
        </tbody>
      </table>
    )
  }
}

export default CollegeTable
