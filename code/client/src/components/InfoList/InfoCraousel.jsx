import React from 'react'
import { observer } from 'mobx-react'
import './InfoCraousel.css'
import { showingStyle, dot_config, long } from '@config/component/Craousel'

@observer
class InfoCraousel extends React.Component {
  constructor(props) {
    super(props)
    this.state = {
      showing: true,
      page: 0
    }
    this.turnPage = this.turnPage.bind(this)
    this.turnToPage = this.turnToPage.bind(this)
  }
  turnToPage(targetPage) {
    return this.turnPage(targetPage - this.state.page)
  }
  turnPage(direction) {
    return () => {
      let { page } = this.state
      if ((page === 0 && direction < 0) || (page > (this.props.list.length - 1) / long - 1 && direction > 0)) 
        return
      this.setState({showing: false})
      page += direction
      setTimeout(() => this.setState({ page, showing: true}), 200)
    }
  }
  render() {
    let { showing, page } = this.state
    let { list, keys } = this.props
    let length = list.length

    let content = []
    for (let i = page * long; i < length && i < (page + 1) * long; i++) {
      let key = `${keys}-rows-${i}-`
      content.push(
        <div className='row aside' key={key}>
          {list[i].map(text => <span className='info-slider-text' key={key + text}>{text}</span>)}
        </div>
      )
    }

    let dots = []
    for (let i = 0; i <= (length - 1) / long; i++) 
      dots.push(<div {...dot_config(i, page, keys)} onClick={this.turnToPage(i)}/>)

    return (
      <div className='info-slider'>
        <div className='info-slider-list column' style={showingStyle(showing)}>{content}</div>
        <div className='row aside info-slider-bar'>
          <span className='responsive info-slider-char pointer' onClick={this.turnPage(-1)}>◀</span>
          <span>{dots}</span>
          <span className='responsive info-slider-char pointer' onClick={this.turnPage(1)}>▶</span>
        </div>
      </div>
    )
  }
}

export default InfoCraousel