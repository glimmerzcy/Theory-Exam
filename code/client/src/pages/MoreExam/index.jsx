import React from 'react'
import Store from '@utils/Store'
import InfoList from '@components/InfoList/InfoList'
import { mypaper } from '@config/page/Student' 
import Banner from '@components/Banner'

class MoreExam extends React.Component{
  render(){
    window.CIAB('student')
    return (
      <div>
        <Banner />
        <InfoList title='源源不断的考试' table={mypaper.table} assign={Store.infoList.mypaper} />
      </div>
    )
  }
}

export default MoreExam