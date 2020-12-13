import React from 'react'
import { observer } from 'mobx-react'
import Store from '.@utils/Store'
import './Header.css'
import config from '@config/component/Header'
import Route  from '@config/RouteConfig.js'

@observer
class Header extends React.Component {
    async componentDidMount() {
        await Store.request('log/status')()
    }
    render() {
        let inner = config[Store.state.status]
        return (
            <div className='header row aside'>
                <span title='点击返回首页' className='row' onClick={() => window.navigateTo(Route.Student.route)}>
                    <img className='responsive logo' src={require('../assets/logo.png')} alt='logp' />
                    <span className='responsive header-text header-title'>理论答题</span>
                </span>
                <div className='row'>
                    <span className='responsive header-text' onClick={inner[0].onClick}>{inner[0].text()}</span>
                    <span className='responsive header-text' onClick={inner[1].onClick}>{inner[1].text()}</span>
                    <span className='responsive header-text' onClick={Store.request('log/out')} style={inner[2]}>退出</span>
                </div>
            </div>
        )
    }
}

export default Header
