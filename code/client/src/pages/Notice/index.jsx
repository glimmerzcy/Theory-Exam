import React from 'react'
import url from 'url'
import { observer } from 'mobx-react'
import './Notice.css'

import Craousel from '@components/InfoList/InfoCraousel'
import Title from '@components/InfoList/InfoTitle'

import Store from '@utils/Store'

const cursor = { cursor: 'pointer', maxWidth: '200px', maxHeight: '2em', overflow: 'hidden' }

const parseNotice = notice => notice.map(e => [<div onClick={() => window.navigateTo(e.url)} style={cursor}>{e.info}</div>, e.time])

let id = -1

@observer
class Notice extends React.Component {
    render() {
        let url_id = url.parse(this.props.location.search, true).query.id
        if (url_id !== id) {
            id = url_id
        }
        let { info, time, detail } = Store.infoList.notice[id] ? Store.infoList.notice[id] : {}
        return (
            <div>
                <div className='row aside'>
                    <div className='notice-content'>
                        <div className='notice-info notice-text'>{info}</div>
                        <div className='notice-time notice-text'>{time}</div>
                        <div className='notice-detail notice-text'>{detail}</div>
                    </div>
                    <div className='notice-craousel'>
                        <Title title='通知公告' />
                        <Craousel key='notice' list={parseNotice(Store.infoList.notice)} />
                    </div>
                </div>
            </div>
        )
    }
}

export default Notice
