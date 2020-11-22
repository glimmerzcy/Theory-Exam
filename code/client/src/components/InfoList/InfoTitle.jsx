import React from 'react'
import './InfoTitle.css'

class InfoTitle extends React.Component {
  render() {
    let {title,more} = this.props
    return (
      <div className='info-title row aside'>
        <span className='info-title-text'>{title}</span>
        {more && <button onClick={() => window.navigateTo(more)} className='responsive info-title-bn'>more</button>}
      </div>
    )
  }
}

export default InfoTitle