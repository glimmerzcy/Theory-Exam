import React from 'react'
import { observer } from 'mobx-react'
import './InfoTable.css'

@observer
class TableRow extends React.Component {
  render() {
    let {assign,keyo} = this.props
    return assign.map((elm,i) =>
      <td key={keyo + i}  className='info-table-cell'>{elm}</td>
    )
  }
}

@observer
class InfoTable extends React.Component {
  render() {
    let {head,assign,keyo} = this.props
    let contentKey = `${keyo}-head-`
    return (
      <tbody>
        <tr className='info-table-head'><TableRow keyo={contentKey} assign={head} /></tr>
        {
          assign.map((elm,i) =>
            <tr key={keyo + i + '-'} className='info-table-row'>
              <TableRow keyo={keyo+ i + '-'} assign={elm} />
            </tr>
          )
        }
      </tbody>
    )
  }
}

export default InfoTable