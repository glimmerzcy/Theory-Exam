import React from 'react'
import { observer } from 'mobx-react'
import InfoItem from './InfoItem'
import InfoTable from './InfoTable'
import InfoTitle from './InfoTitle'

import './InfoList.css'
import './InfoTitle.css'

@observer
class InfoList extends React.Component {
  render() {
    let { more, title, assign, table } = this.props
    return (
      <div className='column'>
        <InfoTitle more={more} title={title} />
        {
          Object.keys(assign).filter(key => key < 5).map(key =>
            table
              ?
              <div key={title + key}>
                <InfoItem key={'info-i-' + title + key} assign={table[key].title} />
                <table className='info-table'>
                  <InfoTable keyo={'table-' + title + key} head={table[key].head} assign={assign[key]} />
                </table>
              </div>
              :
              <InfoItem key={title + key} assign={assign[key]} />
          )
        }
      </div>
    )
  }
}

export default InfoList