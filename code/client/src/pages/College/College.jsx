import React from 'react'

import Index from '../Index/Index'

class Student extends React.Component {
  render() {
    window.CIAB('college')
    return (
      <div>
        <Index />
      </div>
    )
  }
}

export default Student