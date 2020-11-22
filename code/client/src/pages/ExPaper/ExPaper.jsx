import React from 'react'
import { observer } from 'mobx-react'
import './ExPaper.css'

import Title from '../../components/InfoList/InfoTitle'
import Card from '../../components/ExCard'
import ConfigModal from '../../components/PaperConfig/index'

import '../../components/ExCard.css'
import Route from '../../config/RouteConfig'

import { filters, sorts } from '../../config/page/ExPaper'
import Store from '../../utils/Store'
import { Paper } from '../../config/ClassDefine'

function select(obj, target, that) {
  let func = e => {
    let state = {}
    state[target] = obj[e.target.value].func
    that.setState(state)
  }
  return (
    <select className='expaper-fser' onChange={func}>
      {obj.map((elm, i) => <option key={target + i} value={i}>{elm.text}</option>)}
    </select>
  )
}

@observer
class ExPaper extends React.Component {
  constructor(props) {
    super(props)
    this.state = {
      filter: () => true,
      sort: () => 0
    }
  }
  add = () => {
    Store.infoList.paper = new Paper()
    window.showView(<ConfigModal onConfirm={() => window.navigateTo(Route.EditPaper.route)} />)
  }
  componentWillMount() {
    Store.request('expaper/all')()
  }
  render() {
    let { filter, sort } = this.state
    let cards = Store.infoList.all_paper.filter(filter).sort(sort);
    let FSer = (
      <div className='row'>
        {select(filters, 'filter', this)}
        {select(sorts, 'sort', this)}
      </div>
    )
    return (
      <div className='container'>
        <div className='expaper-title'><Title title={FSer} /></div>
        <div className='row expaper-cards'>
          {
            cards.map((elm, i) =>
              <div className='expaper-card'>
                <Card key={'excard-' + i} assign={elm} />
              </div>
            )
          }
          <div className='expaper-card excard expaper-add' onClick={this.add}>
            <img src={require('../../assets/add-dark.png')} />
          </div>
        </div>
      </div>
    )
  }
}

export default ExPaper