import React from 'react'
import { withRouter } from "react-router-dom";
import Store from './Store'
import Route from '../config/RouteConfig'

@withRouter
class Navigator extends React.Component {
  constructor(props) {
    super(props)
    window.navigateTo = url =>
      url.startsWith('~')
        ? window.open('about:blank').location.href = url.slice(1)
        : url.startsWith('h')
          ? window.location.href = url
          : this.props.history.push(url)
    window.CIAB = premittedStatus => Store.state.status !== premittedStatus && window.navigateTo(Route.Index.route)
    window.navigateBack = () => this.props.history.go(-1)
  }
  render() {
    return <div />
  }
}

export default Navigator
//used to bind global navigator to window

//CIAB = Cross-identity access Block
