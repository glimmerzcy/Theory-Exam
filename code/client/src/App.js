//Main Dependency
import React from 'react'
import { BrowserRouter as Router, Route, Switch } from "react-router-dom";
import { observer } from "mobx-react"

//CSS
import './App.css'

//Global Component
import Header from '@components/Header'
// import Footer from '@components/Footer'
import Navigator from '@utils/Navigator'
import InnerWindow from '@utils/InnerWindow/InnerWindow'

//Page
import RouteConfig from '@config/RouteConfig'

//State Manager Store
import Store from '@utils/Store'

//bind for test
window.Store = Store

function routes() {
  let arr = []
  Object.getOwnPropertyNames(RouteConfig).forEach(key =>
    arr.push(<Route key={RouteConfig[key].route} exact path={RouteConfig[key].route} component={RouteConfig[key].page} />)
  )
  return arr
}

@observer
class App extends React.Component {
  onContextMenu = e => Store.state.inExam && window.getSelection().toString() !== '' && e.preventDefault()
  render() {
    return (
      <div onContextMenu={this.onContextMenu}>
        <Router>
          <Navigator />
          <InnerWindow />
          <Header />
          <div className='innerPage'>
            <Switch>{routes()}</Switch>
          </div>
          {/* <Footer /> */}
        </Router>
      </div>
    )
  }
}

export default App
