import React from 'react'
import {observer} from 'mobx-react'

import Index from '../Index'
import InfoList from '@components/InfoList/InfoList'

import Store from '@utils/Store'
import { mypaper } from '@config/page/Student'
import './index.css'

@observer
class Student extends React.Component {
  componentWillMount(){
    
  }
  render() {
    return (
      <div className='stu-index'>
        <Index />
        <div><InfoList title='我的考试' more={mypaper.more()} table={mypaper.table} assign={Store.infoList.mypaper} /></div>
      </div>
    )
  }
}

export default Student
